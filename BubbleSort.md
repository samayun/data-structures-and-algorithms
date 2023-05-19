# BubbleSort

### Code

```c++
#include <iostream>

using namespace std;

void bubbleSort(int arr[], int size) {
    for (int i = 0; i < size - 1; i++) {
        for (int j = 0; j < size - i - 1; j++) {
            if (arr[j] > arr[j + 1]) {
                 swap(arr[j], arr[j + 1]);
            }
        }
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
    int arr[] = { 64, 34, 25, 12, 22, 11, 90 };
    int size = sizeof(arr) / sizeof(arr[0]);

    cout << "Before sorting:" << endl;
    displayArray(arr, size);

    bubbleSort(arr, size);

    cout << "After sorting:" << endl;
    displayArray(arr, size);

    return 0;
}


```

### How to run

```bash

g++ BubbleSort.cpp -o output/BubbleSort && ./output/BubbleSort

```
