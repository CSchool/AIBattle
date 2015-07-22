#ifndef EXECUTION_H
#define EXECUTION_H

#include <string>
#ifndef _WIN32
#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <sys/resource.h>
#endif

enum ExecutionResult
{
    ER_OK, // ���������� ����������
    ER_TL, // ��������� ����� ����������
    ER_RE, // ������ ������� ����������
    ER_ML, // �������� ���������� ����� ������
    ER_IE, // ���������� ������ (��������� ���� ������)
    ER_SV, // Security violation
    // �������������� ���� - �� ������������ � ������� runProcess
    ER_IM, // ������������ ���
    ER_WIN, // ����������
    ER_TIE, // �����
};

/*! Executes the specified process with limitations.
  \param exe Executable to run         
  \param input Input data                
  \param output Output data               
  \param timeLimit Time Limit in milliseconds
  \param memoryLimit Memory Limit in kilobytes  
*/
ExecutionResult runProcess(const std::string &exe, const std::string &input,
    std::string &output, int timeLimit, int memoryLimit);

void printLog(bool first, ExecutionResult result, const std::string &output);
void printField(const std::string &output);
void printInput(bool first, const std::string &input);
void printAnimation(const std::string &output);
void printAnimationStart();
void printAnimationEnd();

#endif
