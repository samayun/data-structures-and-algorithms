#include <iostream>
#include <vector>
#include <algorithm>

using namespace std;

// Structure to represent an edge in the graph
struct Edge {
    int src, dest, weight;
};

// Structure to represent a disjoint set
struct DisjointSet {
    vector<int> parent, rank;
    
    DisjointSet(int n) {
        parent.resize(n);
        rank.resize(n, 0);
        
        for (int i = 0; i < n; i++)
            parent[i] = i;
    }
    
    int find(int x) {
        if (parent[x] != x)
            parent[x] = find(parent[x]);
        return parent[x];
    }
    
    void Union(int x, int y) {
        int xRoot = find(x);
        int yRoot = find(y);
        
        if (rank[xRoot] < rank[yRoot])
            parent[xRoot] = yRoot;
        else if (rank[xRoot] > rank[yRoot])
            parent[yRoot] = xRoot;
        else {
            parent[yRoot] = xRoot;
            rank[xRoot]++;
        }
    }
};

// Comparator to sort edges based on weight
bool compareEdges(const Edge& a, const Edge& b) {
    return a.weight < b.weight;
}

// Function to find the minimum spanning tree using Kruskal's algorithm
void kruskalMST(vector<Edge>& edges, int numNodes) {
    sort(edges.begin(), edges.end(), compareEdges);
    
    DisjointSet ds(numNodes);
    vector<Edge> mst;
    
    for (const auto& edge : edges) {
        int srcParent = ds.find(edge.src);
        int destParent = ds.find(edge.dest);
        
        if (srcParent != destParent) {
            mst.push_back(edge);
            ds.Union(srcParent, destParent);
        }
    }
    
    cout << "Edge \tWeight\n";
    for (const auto& edge : mst) {
        cout << edge.src << " - " << edge.dest << "\t" << edge.weight << "\n";
    }
}

int main() {
    int numNodes, numEdges;
    cout << "Enter the number of nodes: ";
    cin >> numNodes;
    cout << "Enter the number of edges: ";
    cin >> numEdges;

    vector<Edge> edges(numEdges);
    
    cout << "Enter the edges (source destination weight):\n";
    for (int i = 0; i < numEdges; i++) {
        cin >> edges[i].src >> edges[i].dest >> edges[i].weight;
    }

    kruskalMST(edges, numNodes);

    return 0;
}
