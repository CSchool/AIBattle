#include <sstream>
#include <iostream>
#include "execution.h"
#include <list>
#include <map>
#include <cmath>
#include <algorithm>
#include <cstdlib>
#include <ctime>

using namespace std;

// �������� �����

const int fieldSize = 10; // ������ ����
const int maxMoves = 555; // ������������ ���������� �����

enum FieldType 
{
	EMPTY, // ������ ������
	FIRST_PLAYER_TOWER, // ����� ������� ������
	SECOND_PLAYER_TOWER, // ����� ������� ������
	FIRST_PLAYER_LOCATION, // �������������� ������� ������
	SECOND_PLAYER_LOCATION, // �������������� ������� ������
	FIRST_PLAYER_CANNON_FIRST_TYPE, // ����� 1-��� ������ ������� ������
	FIRST_PLAYER_CANNON_SECOND_TYPE, // ����� 2-��� ������ ������� ������
	FIRST_PLAYER_CANNON_THIRD_TYPE, // ����� 3-��� ������ ������� ������
	SECOND_PLAYER_CANNON_FIRST_TYPE, // ����� 1-��� ������ ������� ������
	SECOND_PLAYER_CANNON_SECOND_TYPE, // ����� 2-��� ������ ������� ������
	SECOND_PLAYER_CANNON_THIRD_TYPE, // ����� 3-��� ������ ������� ������
	GOLD // ������� �������
};


FieldType field[fieldSize][fieldSize];

// ������ ��� �����

// ��������� "����������"
struct Point
{
	int x,y;

	Point() : x(0), y(0) {}

	Point(int x, int y)
	{
		this->x = x;
		this->y = y;
	}

	// �������� ������������ �����
	static bool isCorrectPoint(int x, int y)
	{
		return x >= 0 && x < fieldSize && y >= 0 && y < fieldSize;
	}
	
	bool operator==(const Point &p) const
	{
		return x == p.x && y == p.y;
	}

	bool operator<(const Point &p) const
	{
		if (y < p.y)
			return true;
		else if (y > p.y)
			return false;
		else if (x < p.x)
			return true;
		else
			return false;
	}

	bool isNormalPoint()
	{
		return x != -INT_MAX && y != -INT_MAX;
	}
};

// �������������� ����������
bool dist(const Point &p1, const Point &p2, int radius)
{
	return abs(p1.x - p2.x) + abs(p1.y - p2.y) <= radius;
}

int dist(const Point &p1, const Point &p2)
{
	return abs(p1.x - p2.x) + abs(p1.y - p2.y);
}

enum CannonDir {DR, DL, UL, UR}; // Down-Right, Down-Left, Up-Left, Up-Right

enum CannonType {FIRST, SECOND, THIRD, UNKNOW}; // ���� ����� (������, ������, ������)

const int cannonCost[] = {3, 7, 12}; // ��������� �����
const int cannonScore[] = {2, 4, 7}; // ���� �� �����

// ��������� �����
struct Cannon
{
	CannonType type;
	int health, radius, power;

	Cannon() : type(UNKNOW), health(0), radius(0), power(0) {}

	Cannon(CannonType cannonType)
	{
		type = cannonType;
		switch (type)
		{
		case FIRST:
			health = 9;	radius = 1;	power = 2;
			break;
		case SECOND:
			health = 12; radius = 2; power = 3;
			break;
		case THIRD:
			health = 5;	radius = 3;	power = 4;
			break;
		default:
			break;
		}
	}
};

// typedef ��� ����� �����
typedef map<Point, Cannon> CannonMap;

// #####

// ������ ��� �������

struct Player
{
	int gold, score, towerHealth;
	Point position;
	Player() : gold(0), score(0), towerHealth(100), position(-1, -1) {}
};

// #####

// ����� ���������� 

CannonMap cannons[2]; // 0 - ������ �����, 1 - ������ �����, � ������ ������� ������ ����� ������
Player playersInformation[2]; // ��� �������� ���������� � ������� 

// �������

// �������� �������� ����� �� ����������
int getTowerHealth(int player, int x, int y)
{
	CannonMap playerCannons = cannons[player];
	
	CannonMap::iterator it = playerCannons.find(Point(x, y));
	if (it != playerCannons.end())
		return it->second.health;
	else 
		return -1;
}

// �������� ������������� ��� ���� �������� �����
int getTowerHealthFieldPresentation(FieldType cannonType, int x, int y)
{
	int originalHealth = -1;
	
	switch (cannonType)
	{
	case FIRST_PLAYER_CANNON_FIRST_TYPE:
	case FIRST_PLAYER_CANNON_SECOND_TYPE:
	case FIRST_PLAYER_CANNON_THIRD_TYPE:
		originalHealth = getTowerHealth(0, x, y);
		break;
	case SECOND_PLAYER_CANNON_FIRST_TYPE:
	case SECOND_PLAYER_CANNON_SECOND_TYPE:
	case SECOND_PLAYER_CANNON_THIRD_TYPE:
		originalHealth = getTowerHealth(1, x, y);
		break;
	}

	if (originalHealth == -1)
		return 0;
	else
		switch (cannonType)
		{
			case FIRST_PLAYER_CANNON_FIRST_TYPE:
				originalHealth += 200;
				break;
			case FIRST_PLAYER_CANNON_SECOND_TYPE:
				originalHealth += 300;
				break;
			case FIRST_PLAYER_CANNON_THIRD_TYPE:
				originalHealth += 400;
				break;
			case SECOND_PLAYER_CANNON_FIRST_TYPE:
				originalHealth += 600;
				break;
			case SECOND_PLAYER_CANNON_SECOND_TYPE:
				originalHealth += 700;
				break;
			case SECOND_PLAYER_CANNON_THIRD_TYPE:
				originalHealth += 800;
				break;
		}
	return originalHealth;
}

// �������� ����������� ������������� ������ (� ������ ����� ���������� �������� ����������!)
int convertFieldType(FieldType fieldType, int x = -1, int y = -1)
{
	if (x == -1 || y == -1)
		switch (fieldType)
		{
		case EMPTY:
			return 0;
			break;
		case FIRST_PLAYER_TOWER:
			return playersInformation[0].towerHealth;
			break;
		case SECOND_PLAYER_TOWER:
			return playersInformation[1].towerHealth;
			break;
		case FIRST_PLAYER_LOCATION:
			return 500;
			break;
		case SECOND_PLAYER_LOCATION:
			return 900;
			break;
		case GOLD:
			return 1000;
			break;
		}
	else
		return getTowerHealthFieldPresentation(fieldType, x, y);
}

void initField()
{
	for (int i = 0; i < fieldSize; ++i)
		for (int j = 0; j < fieldSize; ++j)
			field[i][j] = EMPTY;

	field[0][0] = FIRST_PLAYER_LOCATION;
	field[9][9] = SECOND_PLAYER_LOCATION;
	field[2][2] = FIRST_PLAYER_TOWER;
	field[7][7] = SECOND_PLAYER_TOWER;

	playersInformation[0].position = Point(0, 0);
	playersInformation[1].position = Point(9, 9);
}

void getField(std::ostringstream &outs)
{
	for (int i = 0; i < fieldSize; ++i)
	{
		for (int j = 0; j < fieldSize; ++j)
			switch (field[i][j])
			{
			case FIRST_PLAYER_CANNON_FIRST_TYPE:
			case FIRST_PLAYER_CANNON_SECOND_TYPE:
			case FIRST_PLAYER_CANNON_THIRD_TYPE:
			case SECOND_PLAYER_CANNON_FIRST_TYPE:
			case SECOND_PLAYER_CANNON_SECOND_TYPE:
			case SECOND_PLAYER_CANNON_THIRD_TYPE:
				outs << convertFieldType(field[i][j], j, i) << " ";
				break;
			default:
				outs << convertFieldType(field[i][j]) << " ";
			}

		outs << "\n";
	}
}

//�������� ������
ExecutionResult checkMovement(bool firstPlayer, istringstream &ins, std::string &result)
{
	char movement;
	ins >> movement;

	int dx = -2, dy = -2;
	int playerIndex = firstPlayer == true ? 0 : 1;

	switch (movement)
	{
	case 'U':
		dy = -1;
		dx = 0;
		break;
	case 'L':
		dy = 0;
		dx = -1;
		break;
	case 'R':
		dy = 0;
		dx = 1;
		break;
	case 'D':
		dy = 1;
		dx = 0;
		break;
	}

	ostringstream outs;
	outs << "M " << movement << std::endl;
	result = outs.str();

	if (dy != -2 && dx != -2)
	{
		// � ��� ���� ����������� ����������� �������� ������
		int y = playersInformation[playerIndex].position.y;
		int x = playersInformation[playerIndex].position.x;

		if (Point::isCorrectPoint(x + dx, y + dy))
		{
			// � ��� ���������� ��� � ����� ������ ������ ������� (������ ���� ����� �����)
			if (field[y + dy][x + dx] == EMPTY || field[y + dy][x + dx] == GOLD)
			{
				FieldType currentPlayer = field[y][x];
				FieldType nextTurn = field[y + dy][x + dx];
				field[y][x] = EMPTY;
				field[y + dy][x + dx] = currentPlayer;

				playersInformation[playerIndex].position.y = y + dy;
				playersInformation[playerIndex].position.x = x + dx;

				if (nextTurn == GOLD)
				{
					playersInformation[playerIndex].gold++;
					playersInformation[playerIndex].score++;
				}

				return ER_OK;
			} else return ER_IM;
		} else return ER_IM;
	} else return ER_IM;
}

bool cannonPossibleToBuild(Point player, Point cannon)
{
	return abs(player.x - cannon.x) <= 1 && abs(player.y - cannon.y) <= 1 && (player.x != cannon.x || player.y != cannon.y); 
}

// ������������� �����
ExecutionResult cannonBuilding(bool firstPlayer, istringstream &ins, std::string &result)
{
	CannonType cannonType = UNKNOW;
	int cx = -2, cy = -2, cannon = UNKNOW;

	ins >> cx >> cy >> cannon;
	cannonType = (CannonType)(cannon - 1);

	ostringstream outs;
	outs << "B " << cx << " " << cy << " " << cannon << std::endl; 

	result = outs.str();

	int playerIndex = firstPlayer == true ? 0 : 1;
	int y = playersInformation[playerIndex].position.y;
	int x = playersInformation[playerIndex].position.x;
	
	if (Point::isCorrectPoint(cx, cy) && cannonPossibleToBuild(playersInformation[playerIndex].position, Point(cx, cy)))
	{
		// �������� �� ��, ��� ������������ ���� ���������� ��������
		if (field[cy][cx] == EMPTY)
		{
			// ����� ����� ���������, ��� ��� ����� ������
			int cost = cannonCost[cannonType];
			if (playersInformation[playerIndex].gold - cost >= 0)
			{
				// � ������ ���� ������ �� �������������
				playersInformation[playerIndex].gold -= cost;
				
				cannons[playerIndex].insert(make_pair(Point(cx, cy), Cannon(cannonType)));
				playersInformation[playerIndex].score += cannonScore[cannonType];

				FieldType cannonOnField = EMPTY;

				if (firstPlayer)
					switch (cannonType)
					{
					case FIRST:
						cannonOnField = FIRST_PLAYER_CANNON_FIRST_TYPE;
						break;
					case SECOND:
						cannonOnField = FIRST_PLAYER_CANNON_SECOND_TYPE;
						break;
					case THIRD:
						cannonOnField = FIRST_PLAYER_CANNON_THIRD_TYPE;
						break;
					}
				else
					switch (cannonType)
					{
					case FIRST:
						cannonOnField = SECOND_PLAYER_CANNON_FIRST_TYPE;
						break;
					case SECOND:
						cannonOnField = SECOND_PLAYER_CANNON_SECOND_TYPE;
						break;
					case THIRD:
						cannonOnField = SECOND_PLAYER_CANNON_THIRD_TYPE;
						break;
					}

				field[cy][cx] = cannonOnField;

				return ER_OK;

			} 
			else return ER_IM;
		} 
		else return ER_IM;
	} 
	else return ER_IM;

}

// ���������� ���� ������
ExecutionResult playerMove(bool firstPlayer, const char* program, std::string &result)
{
	ostringstream outs;
	string output;

	int player = firstPlayer == true ? 0 : 1;
	int enemy = 1 - player;

	outs << player + 1 << " " << playersInformation[player].gold << " " << playersInformation[player].score  << "\n";
	getField(outs);

	printInput(firstPlayer, outs.str());

	//cout << outs.str() << endl;

	ExecutionResult execResult = runProcess(program, outs.str(), output, 1000, 64000); 
		
	if (execResult == ER_OK)
	{
		istringstream ins(output);
		char mode;

		ins >> mode;
		switch (mode)
		{
		case 'S':
			result = "S";
			return ER_OK;
			// ������ �� ������
			break;
		case 'M':
			// �������� ������
			return checkMovement(firstPlayer, ins, result);
		case 'B':
			// ������������� �����
			return cannonBuilding(firstPlayer, ins, result);
		default:
			result = mode;
			return ER_IM;
			// �����-�� ������������ ������� - ������ �� ������
		}
	}
	return ER_OK;
}

bool checkCycleDR(int dx, int value)
{
	return dx < value; 
}

const int cannonCheckDX[] = {1, -1, -1, 1};
const int cannonCheckDY[] = {1, 1, -1, -1};

bool isPointContainsCannon(const Point &point, int enemy)
{
	// 0 -- ������ �����, 1 -- ������
	int x = point.x, y = point.y;
	bool result = false;
	switch (enemy)
	{
	case 0:
		result = (field[y][x] == FIRST_PLAYER_CANNON_FIRST_TYPE || 
				 field[y][x] == FIRST_PLAYER_CANNON_SECOND_TYPE ||
				 field[y][x] == FIRST_PLAYER_CANNON_THIRD_TYPE);
		break;
	case 1:
		result = (field[y][x] == SECOND_PLAYER_CANNON_FIRST_TYPE || 
				 field[y][x] == SECOND_PLAYER_CANNON_SECOND_TYPE ||
				 field[y][x] == SECOND_PLAYER_CANNON_THIRD_TYPE);
		break;
	}

	return result;
}

Point checkNearbyCannos(Point cannon, int enemy, int radius)
{
	Point damagedCannonPoint(-INT_MAX, -INT_MAX);
	int dx = 0, dy = -radius;

	//cout << "cannon: (" << cannon.x << ", " << cannon.y << ") - currentRadius: " << radius << endl;

	for (int dir = 0; dir < 4; ++dir)
		for (int i = 0; i < radius; ++i)
		{
			//cout << "dx: " << dx << ", dy: " << dy << ", i: " << i << ", dir: " << dir << ", enemy: " << enemy << endl;
			//cout << "newPoint (" << cannon.x + dx << ", " << cannon.y + dy << ") is " << field[cannon.y + dy][cannon.x + dx] << endl; 
			
			//cout << "correct (" << cannon.x + dx << ", " << cannon.y + dy << ") = " << Point::isCorrectPoint(cannon.x + dx, cannon.y + dy) << endl;
			//cout << "Containing: " << isPointContainsCannon(Point(cannon.x + dx, cannon.y + dy), enemy) << endl;
			
			if (Point::isCorrectPoint(cannon.x + dx, cannon.y + dy) && 
				isPointContainsCannon(Point(cannon.x + dx, cannon.y + dy), enemy))
			{
				damagedCannonPoint = Point(cannon.x + dx, cannon.y + dy);
				//cout << "GOTCHA" << endl;
				return damagedCannonPoint;
			}

			dx += cannonCheckDX[dir];
			dy += cannonCheckDY[dir];
		}

	return damagedCannonPoint;
}



// �������� ����� �� ����
void cannonShooting(std::string &animation)
{

	list<pair<Point, Point>> animationVector;

	// �������� �����
	for (int player = 0; player < 2; ++player)
	{
		int enemy = 1 - player;
		
		for (CannonMap::iterator it = cannons[player].begin(); it != cannons[player].end(); ++it)
		{
			// ������� �� ����� �� ��������� �����
			Point tower(player == 0 ? 7 : 2, player == 0 ? 7 : 2);
			Point cannon = it->first;

			if (dist(cannon, tower, it->second.radius))
			{
				// ������� �� �����
				playersInformation[enemy].towerHealth -= it->second.power;
				animationVector.push_back(make_pair(cannon, tower));
				//cout << cannon.x << " " << cannon.y << " SHOOTS TOWER " << tower.x << " " << tower.y << endl;
			}
			else
			{
				Point nearbyPoint(-INT_MAX, -INT_MAX);
				// �������� ������� �� ������
				for (int i = 1; i <= it->second.radius; ++i) // ����� � ����������� �������
				{
					nearbyPoint = checkNearbyCannos(cannon, enemy, i);
					if (nearbyPoint.isNormalPoint())
						break;
				}

				// �������� ����, ����� ����� ����� ����������
				if (nearbyPoint.isNormalPoint())
				{
					cannons[enemy][nearbyPoint].health -= it->second.power;
					animationVector.push_back(make_pair(cannon, nearbyPoint));
					//cout << cannon.x << " " << cannon.y << " SHOOTS CANNON " << nearbyPoint.x << " " << nearbyPoint.y << endl;
				}
			}
		}
	}

	ostringstream animationStream;

	for (list<pair<Point, Point>>::iterator it = animationVector.begin(); it != animationVector.end(); ++it)
	{
		animationStream << it->first.x << " " << it->first.y << " " << it->second.x << " " << it->second.y << "\n"; 
	}

	animation = animationStream.str();

	// ������� ������� �����
	for (int player = 0; player < 2; ++player)
	{
		list<CannonMap::iterator> deadCannons;
		for (CannonMap::iterator it = cannons[player].begin(); it != cannons[player].end(); ++it)
			if (it->second.health <= 0)
				deadCannons.push_back(it);

		for (list<CannonMap::iterator>::iterator it = deadCannons.begin(); it != deadCannons.end(); ++it)
		{
			int x = (*it)->first.x;
			int y = (*it)->first.y;

			field[y][x] = EMPTY;
			cannons[player].erase((*it)->first);
		}
	}
}

// �������� ��������� ����
bool isGameOver()
{
	return playersInformation[0].towerHealth <= 0 || playersInformation[1].towerHealth <= 0;
}

// ����� �������
void spawnGold()
{
	int count = fieldSize * fieldSize;
	int i,j;

	do
	{
		i = rand() % fieldSize;
		j = rand() % fieldSize;
	} while (--count && field[i][j] != EMPTY);

	if (count)
		field[i][j] = GOLD;
}

void debugInformation()
{
	cout << "DEBUG" << endl;
	cout << "Towers:" << endl;
	
	const char* p[] = {"First:", "Second:"};

	for (int i = 0; i < 2; ++i)
	{
		cout << p[i] << endl;
		for (CannonMap::iterator it = cannons[i].begin(); it != cannons[i].end(); ++it)
		{
			cout << "   " << it->first.x << " " << it->first.y << " -> " << it->second.health << " " << it->second.radius << endl;
		}
	}

	cout << "END_DEBUG" << endl;
}

int main(int argc, char **argv)
{
	
	srand(time(NULL));

	
	
	if (argc != 3)
    {
        std::cout << "Usage: TW_tester <program1> <program2>\n";
        return 1;
    }
	

	//const char *program1 = "C:\\Users\\Sergey_2\\Documents\\CmakeBuild\\AIBattle\\TowerDefense\\Debug\\TWSample.exe";
    //const char *program2 = "C:\\Users\\Sergey_2\\Documents\\CmakeBuild\\AIBattle\\TowerDefense\\Debug\\TWSample.exe";//argv[2];


	const char *program1 = argv[1]; 
    const char *program2 = argv[2];

	// �������������� ����
	initField();

	std::string animation;

	int moves = 0;

	for (; moves < maxMoves; ++moves)
	{
		animation.clear();

		ostringstream outs;
		getField(outs);
		outs << playersInformation[0].gold << " " << playersInformation[0].score << "\n";
		outs << playersInformation[1].gold << " " << playersInformation[1].score << "\n";
		printField(outs.str());
		

		// ���� �������
		std::string output1, output2;

		ExecutionResult exec1 = playerMove(true, program1, output1);

		printLog(true, exec1, output1);

		ExecutionResult exec2 = playerMove(false, program2, output2);

		printLog(false, exec2, output2);

		// �������� �����
		
		cannonShooting(animation);

		//debugInformation();

		printAnimationStart();
		printAnimation(animation);
		printAnimationEnd();

		// ������� ��������� ����
		if (!isGameOver())
		{
			if (moves % 3 == 0)
				spawnGold(); // ������� �������	
		}
		else
		{
			// ����� ��� � ��� �������, � ��� ��������
			if (playersInformation[0].towerHealth <= 0 && playersInformation[1].towerHealth <= 0)
				printLog(true, ER_TIE, "");
			else if (playersInformation[0].towerHealth <= 0)
				printLog(false, ER_WIN, "");
			else
				printLog(true, ER_WIN, "");

			return 0;
		}		
	}

	// �������� ��� ������� ���������� �����

	if (playersInformation[0].score > playersInformation[1].score)
		printLog(true, ER_WIN, std::string(playersInformation[0].score + " - " + playersInformation[1].score));
	else if (playersInformation[1].score > playersInformation[0].score)
		printLog(false, ER_WIN, std::string(playersInformation[0].score + " - " + playersInformation[1].score));
	else
		printLog(true, ER_TIE, "");

    return 0;
}
