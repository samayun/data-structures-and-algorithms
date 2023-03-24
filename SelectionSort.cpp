#include <iostream>
using namespace std;


int selectionSort(int A[] , int size, int type ){
  int i,j, minMax;
  for(j=0; j< size; j++){
    minMax = j;
    for(i=j+1; i < size; i++){
      if(A[i] < A[minMax]){
        minMax = i;
      }
    }
    // 1 for MAX value
    if(type == 1){
      swap(A[j] , A[minMax]);
    } else {
      // 0 for MIN value
      swap(A[i] , A[minMax]);
    }
    
  }
  return A[minMax];
}

int main() {
int students[] = { 10, 20, 30, 40,5,8,2,2};
    cout << selectionSort(students, 8, 0);
    return 0;
}
