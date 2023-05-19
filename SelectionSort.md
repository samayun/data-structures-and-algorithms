# SelectionSort

### Code

```c++

#include <iostream>

using namespace std;

void selectionSort(int arr[], int size) {
    for (int i = 0; i < size - 1; i++) {
        int minIndex = i;

        for (int j = i + 1; j < size; j++) {
            if (arr[j] < arr[minIndex]) {
                minIndex = j;
            }
        }
        swap(arr[i], arr[minIndex]);
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
    int myArray[] = { 64, 34, 25, 12, 22, 11, 90 };
    int size = sizeof(myArray) / sizeof(myArray[0]);

    cout << "Before sorting:" << endl;
    displayArray(myArray, size);

    selectionSort(myArray, size);

    cout << "After sorting:" << endl;
    displayArray(myArray, size);

    return 0;
}


```

### How to run

```bash

g++ SelectionSort.cpp -o output/SelectionSort && ./output/SelectionSort

```
