# BinarySearch

### Code

```c++

#include <iostream>

using namespace std;

/*
* int arr[] is the array to be searched
* @return index
*/

int binarySearch(int arr[], int left, int right, int target) {
    while (left <= right) {
        int mid = left + (right - left) / 2;

        if (arr[mid] == target) {
            return mid;
        }

        if (arr[mid] < target) {
            left = mid + 1;
        } else {
            right = mid - 1;
        }
    }

    return -1;
}

int main() {
    int samuArray[] = {2, 5, 10, 15, 20, 30, 40};
    int size = sizeof(samuArray) / sizeof(samuArray[0]);
    int target = 20;

    int result = binarySearch(samuArray, 0, size - 1, target);

    if (result == -1) {
        cout << "Element " << target << " not found in the array." << endl;
    } else {
        cout << "Element " << target << " found at index " << result << "." << endl;
    }

    return 0;
}

```

### How to run

```bash

g++ BinarySearch.cpp -o output/BinarySearch && ./output/BinarySearch

```
