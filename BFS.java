/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 */

package dsa.lab;
import java.util.Scanner;
/**
 *
 * @author student_user
 */
public class Lab {
    private static void printMatrix(int[][] matrix) {
        for (int i = 0; i < matrix.length; i++) {
            for (int j = 0; j < matrix[0].length; j++) {
                System.out.print(matrix[i][j] + " | ");
            }
            System.out.println();
        }
    }
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        int [][] mat = new int[5][5];
        int edge=7,i=0,a,b,j,node=5;
        
        for(i=0;i<edge;i++) {
            a = sc.nextInt();
            b = sc.nextInt();
            mat[a][b] = mat[b][a] = 1;
        }
        printMatrix(mat);
    }
}
