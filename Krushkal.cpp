#include <iostream>

using namespace std;

int weightedG[10][10] = {0}, u, v, parent[10]={0}, a, b;
int vertices, edges, k, row, column, step=1, Min, mincost = 0;

int findparent(int i)
{
	while(parent[i])
	i=parent[i];
	return i;
}

int check_cycle(int i, int j)
{
	if(i!=j)
	{
		parent[j]=i;
		return 1;
	}
	return 0;
}

int main()
{
	cout<<"Enter the number of vertices: ";
	cin>>vertices;

	for(row=1; row<=vertices; row++)
	{
		for(column=1; column<=vertices; column++)
		{
			cin>>weightedG[row][column];
			if(weightedG[row][column]==0)
				weightedG[row][column]=100;
		}
	}
	for(row=1; row<=vertices; row++)
	{
		for(column=1; column<=vertices; column++)
		{
			cout<<weightedG[row][column]<<" ";
		}
		cout<<"\n";
	}
	while(step<vertices)
	{
	Min = 100;
	for(row=1; row<=vertices; row++)
	{
		for(column=1; column<=vertices; column++)
		{
			if(weightedG[row][column]<Min)
			{
				Min = weightedG[row][column];
				a =u=row;
				b =v=column;
			}	
		}
	}
	
	u=findparent(u);
	v=findparent(v);
    if(check_cycle(u,v))
	{
		cout<<"Edge ("<<a<<", "<<b<<")="<<Min<<"\n";
		mincost = mincost+Min;
		step++;
	}
	weightedG[a][b] = weightedG[b][a] = 100;
	}
	cout<<"MST cost: "<<mincost;
}
