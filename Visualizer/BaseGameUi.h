#ifndef BASEGAMEUI_H
#define BASEGAMEUI_H

#include <QWidget>
#include <QMainWindow>
#include <QLabel>
#include <QPushButton>
#include <QPainter>
#include <QLayout>
#include <QTextStream>
#include <QMessageBox>
#include <QEvent>
#include <QTimer>
#include <QScrollBar>
#include <QString>
#include "LogChooser.h"

struct LogItem
{
    enum LogItemKind
    {
        K_MOVE,
        K_ERROR,
        K_WIN,
        K_TIE,
        K_FIELD,
        K_ANIMATION
    };
    LogItemKind kind;
    int player;
    QString data;
    QString input;
};

class BaseGameUi : public QWidget
{
	Q_OBJECT
private:
    static const int MAX_TIMER_INTERVAL = 10000;
protected:
    static const int animationTimerInterval = 100;

private:
	QLabel *myText;
	QLabel *scoreText;
protected:
	QLabel *fieldLabel;
	QSize fieldBaseSize;
	QSize getFieldSize();  // ��������� ������� Label'�
	QPixmap *getFieldPixmap(); // ��������� pixmap

    
private:
	LogChooser *logChooser;
	QPushButton *nextTurnButton;
	QPushButton *prevTurnButton;
    QScrollBar *speedScroll;
    QPushButton *startButton;
    QPushButton* resetLogButton;
	QVector<LogItem> history; // ������� �����
	int currentTurn; // ������� ���
    QString gameName;
    QTimer *timer;
    QTimer *animationTimer;
    QString playerName1;
    QString playerName2;

    

    int animationTimerCount;
protected:
    void setScore(int score1, int score2);
    int getAnimationTimerCount();
private:
	virtual void prepareGame() {} // ���������� ����
    virtual void move(int player, const QString &turn) = 0;
    virtual void setField(const QString &field) = 0;
    virtual void drawField() = 0; // ��������� ����
    virtual void setAnimation(const QString &animation) {}
private slots:
    // ��������� ��� �� ���� 
	void nextTurnHandler();
    // ���������� ��� �� ����
	void prevTurnHandler();
    // �������� ���� �� ����
	void getTurnsFromLog();
	void switchAnimation();
    void speedChanged(int value); 
    void checkAnimation();
    void resetLog();
private:
    // ����� (�������������) ���� � ������ ������� � ����
    void resetGame();
    // ��������� ��� �� ���� 
	void nextTurn();
    void startAnimation();
    void stopAnimation();
public:
	BaseGameUi(const QString& name, QSize fieldSize, QWidget *parent = 0, bool hasScore = false);
    ~BaseGameUi();
    void setPreLog(QString path);
};

#endif // BASEGAMEUI_H
