#include <iostream>
using namespace std;


int selectionSort(int A[] , int size, int type ){
  int i,j,smallest;
  for(j=0; j< size; j++){
    smallest = j;
    for(i=j+1; i < size; i++){
      if(A[i] < A[smallest]){
        smallest = i;
      }
    }
    if(type == 1){
      swap(A[j] , A[smallest]);
    } else {
      swap(A[i] , A[smallest]);
    }
    
  }
  return A[smallest];
}

int main() {
int students[] = { 10, 20, 30, 40,5,8,2,2};
    cout << selectionSort(students, 8, 0);
    return 0;
}
