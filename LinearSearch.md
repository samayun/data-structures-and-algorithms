# LinearSearch

### Code

```c++

#include <iostream>

using namespace std;

/*
* int arr[] is the linear array
* size of LA
* target elment is the third parameter
* @return index
*/
int linearSearch(const int arr[], int size, int target) {
    for (int i = 0; i < size; i++) {
        if (arr[i] == target) {
            return i;
        }
    }
    return -1;
}

int main() {
    const int size = 5;
    int arr[size] = {10, 20, 30, 40, 50};
    int target = 30;

    int result = linearSearch(arr, size, target);

    if (result != -1) {
        cout << "Element " << target << " found at index " << result << endl;
    } else {
        cout << "Element " << target << " not found in the array." << endl;
    }

    return 0;
}


```

### How to run

```bash

g++ LinearSearch.cpp -o output/LinearSearch && ./output/LinearSearch

```
