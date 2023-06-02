#include<iostream>

using namespace std;

int weightedG[10][10] = {0}, v, visited[10],j;
int vertices,edges,k,row,column,step = 1, Min = 0,a,b,mincost;

int main()
{
    cout << "Enter vertices: ";
    cin >> vertices;

    for(row = 1; row <= vertices; row++){
       for(column = 1; column <= vertices; column++){
             cin >> weightedG[row][column];
             if(weightedG[row][column] == 0)
                weightedG[row][column] = 100;
      }
    }
    
  cout << "------------------------- " << "\n";

   for(row = 1; row <= vertices; row++){
       for(column = 1; column <= vertices; column++){
          cout << weightedG[row][column] << "\t";
      }
      cout << "\n";
    }

    cout << "Enter the starting vertex: " << "";
    cin >> v;

    while(step < vertices) {
       Min = 100;
         for(row = 1; row <= vertices; row++){
            for(column = 1; column <= vertices; column++){
              if(weightedG[row][column] < Min){
                if(visited[row] == 1){
                    Min = weightedG[row][column];
                    a = row;
                    b = column;
                }
              }
            }
         }

        if(visited[a] == 0 || visited[b] == 0) {
            cout << "Edge (" << a << " , " << b << ")" << Min << "\n";
            mincost = mincost + Min;
            visited[b] = 1;
            step++;
        }
        weightedG[a][b] = weightedG[b][a] = 100;
    }
    
    cout << "MST cost:" << mincost;

    return 0;
}


