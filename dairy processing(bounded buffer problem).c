#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

#Number of processing stages
#define NUM_STAGES 3

# Buffer to simulate shared pipelines and processing equipment
#define BUFFER_SIZE 10
int buffer[BUFFER_SIZE];
int count = 0;

#Mutex lock for synchronizing access to the shared buffer
pthread_mutex_t mutex;

# Function prototypes
void* pasteurization(void* arg);
void* homogenization(void* arg);
void* packaging(void* arg);

int main() {
    // Initialize the mutex
    pthread_mutex_init(&mutex, NULL);

    // Create threads for each processing stage
    pthread_t pasteurization_thread, homogenization_thread, packaging_thread;

    pthread_create(&pasteurization_thread, NULL, pasteurization, NULL);
    pthread_create(&homogenization_thread, NULL, homogenization, NULL);
    pthread_create(&packaging_thread, NULL, packaging, NULL);

    // Wait for threads to finish
    pthread_join(pasteurization_thread, NULL);
    pthread_join(homogenization_thread, NULL);
    pthread_join(packaging_thread, NULL);

    // Destroy the mutex
    pthread_mutex_destroy(&mutex);

    return 0;
}

void* pasteurization(void* arg) {
    while (1) {
        // Simulate pasteurization process
        sleep(1);

        // Lock the mutex before accessing the shared buffer
        pthread_mutex_lock(&mutex);

        if (count < BUFFER_SIZE) {
            buffer[count] = 1; // Simulate adding processed milk to the buffer
            printf("Pasteurization: Added milk to buffer. Count = %d\n", ++count);
        } else {
            printf("Pasteurization: Buffer full, waiting...\n");
        }

        // Unlock the mutex
        pthread_mutex_unlock(&mutex);

        // Simulate processing delay
        sleep(1);
    }
}

void* homogenization(void* arg) {
    while (1) {
        // Simulate homogenization process
        sleep(1);

        // Lock the mutex before accessing the shared buffer
        pthread_mutex_lock(&mutex);

        if (count > 0 && count < BUFFER_SIZE) {
            printf("Homogenization: Processing milk. Count = %d\n", count);
        } else if (count == 0) {
            printf("Homogenization: Buffer empty, waiting...\n");
        }

        // Unlock the mutex
        pthread_mutex_unlock(&mutex);

        // Simulate processing delay
        sleep(1);
    }
}

void* packaging(void* arg) {
    while (1) {
        // Simulate packaging process
        sleep(1);

        // Lock the mutex before accessing the shared buffer
        pthread_mutex_lock(&mutex);

        if (count > 0) {
            printf("Packaging: Packed milk from buffer. Count = %d\n", --count);
        } else {
            printf("Packaging: Buffer empty, waiting...\n");
        }

        // Unlock the mutex
        pthread_mutex_unlock(&mutex);

        // Simulate processing delay
        sleep(1);
    }
}