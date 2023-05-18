// Author: Samayun Chowdhury
// Source Code: https://github.com/samayun/data-structures-and-algorithms/blob/master/queue.cpp

#include <iostream>

const int MAX_SIZE = 10;

class Queue {
private:
    int arr[MAX_SIZE];
    int front;
    int rear;

public:
    Queue() : front(-1), rear(-1) {}

    bool isEmpty() {
        return (front == -1 && rear == -1);
    }

    bool isFull() {
        return (rear == MAX_SIZE - 1);
    }

    void enqueue(int value) {
        if (isFull()) {
            std::cout << "Queue is full" << std::endl;
            return;
        }
        if (isEmpty()) {
            front = 0;
        }
        rear++;
        arr[rear] = value;
        std::cout << "Enqueued element: " << value << std::endl;
    }

    void dequeue() {
        if (isEmpty()) {
            std::cout << "Queue is empty" << std::endl;
            return;
        }
        std::cout << "Dequeued element: " << arr[front] << std::endl;
        if (front == rear) {
            front = rear = -1;
        } else {
            front++;
        }
    }

    void display() {
        if (isEmpty()) {
            std::cout << "Queue is empty." << std::endl;
            return;
        }
        std::cout << "Queue elements: ";
        for (int i = front; i <= rear; i++) {
            std::cout << arr[i] << " ";
        }
        std::cout << std::endl;
    }
};

int main() {
    Queue samuQueue;

    samuQueue.enqueue(99);
    samuQueue.enqueue(17);


    samuQueue.display();

    samuQueue.dequeue();
    samuQueue.dequeue();

    samuQueue.display();

    return 0;
}
