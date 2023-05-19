
// Author: Samayun Chowdhury
// Source Code: https://github.com/samayun/data-structures-and-algorithms/blob/master/Array.cpp


#include <iostream>

using namespace std;

const int MAX_SIZE = 100;

int numArray[MAX_SIZE];
int length;


void insert(int element, int position) {
    if (position < 0 || position > length) {
        cout << "Invalid position. Cannot insert element. " << length << endl;
        return;
    }
    if (length == MAX_SIZE) {
        cout << "Array is full. Cannot insert element." << endl;
        return;
    }
    for (int i = length - 1; i >= position; i--) {
        numArray[i + 1] = numArray[i];
    }
    numArray[position] = element;
    length++;
    cout << "Inserted element: " << element << endl;
}

void remove(int position) {
    if (position < 0 || position >= length) {
        cout << "Invalid position. Cannot remove element." << endl;
        return;
    }
    for (int i = position; i < length - 1; i++) {
        numArray[i] = numArray[i + 1];
    }
    length--;
    cout << "Removed element at position " << position << endl;
}

void display() {
    if (length == 0) {
        cout << "The array is empty." << endl;
        return;
    }
    cout << "All elements: ";
    for (int i = 0; i < length; i++) {
        cout << numArray[i] << " ";
    }
    cout << endl;
}


int main() {
  
    insert(10, 0);
    insert(20, 1);
    insert(30, 2);
    insert(40, 3);
    insert(50, 4);
    insert(200, 98); // trying to insert wrong position should throw error

    display();

    remove(1);

    display();

    return 0;
}
