#include <iostream>
#include <time.h>
using namespace std;

int arr[100], i, j;

void BubbleSort(int arr[], int n)
{

    for (i = 0; i < (n - 1); i++)
    {
        for (j = 0; j < (n - i - 1); j++)
        {
            if (arr[j] > arr[j + 1])
            {
                swap(arr[j], arr[j + 1]);
            }
        }
    }
}

void SelectionSort(int arr[], int n)
{
    int step, small, compare;
    for (step = 0; step < n; step++)
    {
        small = step;
        for (compare = step + 1; compare <= n; compare++)
        {
            if (arr[compare] < arr[small])
                small = compare;
        }
        swap(arr[step], arr[small]);
    }
}

void InsertionSort(int arr[], int n)
{
    int key;
    for (i = 1; i < n; i++)
    {
        key = arr[i];
        j = i - 1;
        while (j >= 0 && arr[j] > key)
        {
            arr[j + 1] = arr[j];
            j = j - 1;
        }
        arr[j + 1] = key;
    }
}

int main()
{
    int choice1, choice2, n;
    do
    {
        cout << "\n\nPress 1 to continue....";
        cout << "\nPress 2 to EXIT";
        cout << "\nEnter your choice: ";
        cin >> choice2;
        switch (choice2)
        {
        case 1:
            cout << "\nEnter the size of the array: ";
            cin >> n;
            arr[n];
            srand(time(0));
            for (int i = 0; i < n; i++)
            {
                arr[i] = rand() % 50;
            }
            cout << "unSorted array: ";
            for (int i = 0; i < n; i++)
            {
                cout << arr[i] << " ";
            }
            cout << "\n## Main Menu ##";
            cout << "\nPress 1 to BubbleSort";
            cout << "\nPress 2 for SelectionSort";
            cout << "\nPress 3 for InsertionSort";
            cout << "\nEnter your choice: ";
            cin >> choice1;
            switch (choice1)
            {
            case 1:
                BubbleSort(arr, n);
                break;
            case 2:
                SelectionSort(arr, n);
                break;
            case 3:
                InsertionSort(arr, n);
                break;
            default:
                cout << "Wrong choice !! Try Again";
                break;
            }
            cout << "Sorted array: ";
            for (int i = 0; i < n; i++)
            {
                cout << arr[i] << " ";
            }
            break;
        case 2:
            cout << "Exit successfully";
            break;

        default:
            cout << "\nWrong choice!! TRY again";
            break;
        }
    } while (choice2 != 2);
}
