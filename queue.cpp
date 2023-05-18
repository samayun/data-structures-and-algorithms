#include <iostream>

const int MAX_SIZE = 5; // Maximum size of the queue

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
            std::cout << "Queue is full. Cannot enqueue element." << std::endl;
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
            std::cout << "Queue is empty. Cannot dequeue element." << std::endl;
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
    Queue myQueue;

    myQueue.enqueue(10);
    myQueue.enqueue(20);
    myQueue.enqueue(30);
    myQueue.enqueue(40);
    myQueue.enqueue(50);

    myQueue.display();

    myQueue.dequeue();
    myQueue.dequeue();

    myQueue.display();

    return 0;
}
