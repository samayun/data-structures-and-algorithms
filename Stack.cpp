/*
 * Author: Samayun Chowdhury
 * Source Code: https://github.com/samayun/data-structures-and-algorithms/blob/master/Stack.cpp
 */

#include <iostream>

using namespace std;

const int MAX_SIZE = 100;

struct Stack
{
    int top;
    int elements[MAX_SIZE];
};

void initialize(Stack &stack)
{
    stack.top = -1;
}

bool isEmpty(const Stack &stack)
{
    return stack.top == -1;
}

bool isFull(const Stack &stack)
{
    return stack.top == MAX_SIZE - 1;
}

void push(Stack &stack, int value)
{
    if (isFull(stack))
    {
        cout << "Stack is full. Cannot push element.\n";
        return;
    }

    stack.elements[++stack.top] = value;
}

int pop(Stack &stack)
{
    if (isEmpty(stack))
    {
        cout << "Stack is empty. Cannot pop element.\n";
        return -1;
    }

    return stack.elements[stack.top--];
}

int peek(const Stack &stack)
{
    if (isEmpty(stack))
    {
        cout << "Stack is empty. Cannot peek element.\n";
        return -1;
    }

    return stack.elements[stack.top];
}

int main()
{
    Stack stack;
    initialize(stack);

    push(stack, 10);
    push(stack, 20);
    push(stack, 30);

    cout << "Top element: " << peek(stack) << endl;

    int popped = pop(stack);
    cout << "Popped element: " << popped << endl;

    cout << "Top element after pop: " << peek(stack) << endl;

    return 0;
}
