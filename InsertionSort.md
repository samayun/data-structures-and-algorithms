# InsertionSort

### Code

```c++


#include <iostream>

using namespace std;

void insertionSort(int arr[], int size) {
    for (int i = 1; i < size; i++) {
        int key = arr[i];
        int j = i - 1;

        while (j >= 0 && arr[j] > key) {
            arr[j + 1] = arr[j];
            j--;
        }

        arr[j + 1] = key;
    }
}

void displayArray(int arr[], int size) {
    cout << "Array elements: ";
    for (int i = 0; i < size; i++) {
        cout << arr[i] << " ";
    }
    cout << endl;
}

int main() {
    int samuArray[] = { 64, 34, 25, 12, 22, 11, 90 };
    int size = sizeof(samuArray) / sizeof(samuArray[0]);

    cout << "Before sorting:" << endl;
    displayArray(samuArray, size);

    insertionSort(samuArray, size);

    cout << "After sorting:" << endl;
    displayArray(samuArray, size);

    return 0;
}

```

### How to run

```bash

g++ InsertionSort.cpp -o output/InsertionSort && ./output/InsertionSort

```
