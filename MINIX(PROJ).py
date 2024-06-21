import streamlit as st
import pandas as pd
import graphviz as gv

# Define Queue class
class Queue:
    def _init_(self):
        self.items = []

    def enqueue(self, item):
        self.items.append(item)

    def dequeue(self):
        if not self.is_empty():
            return self.items.pop(0)
        return None

    def is_empty(self):
        return len(self.items) == 0

    def size(self):
        return len(self.items)

# Define Process1 class
class Process1:
    def _init_(self, size=0):
        self.Pr = Queue()
        self.Ar = Queue()
        self.Bt = Queue()
        self.Ct = Queue()
        self.Tat = Queue()
        self.Wt = Queue()
        self.queue = Queue()
        self.size = size

    def get_size(self):
        return self.size


# Function to initialize ready queue
def readyQueue(process, num):
    for i in range(process.Ar.size()):
        for j in range(process.Ar.size()):
            if process.Ar.items[j] <= num:
                if process.Ar.items[j] == process.Ar.items[j]:
                    if process.Pr.items[j] not in process.queue.items:
                        process.queue.enqueue(process.Pr.items[j])

# Function to execute a CPU burst for a process
def cbtProcess(process, curr, quantum, i, a):
    while process.Bt.items[i] != 0:
        if process.Bt.items[i] >= quantum:
            process.Bt.items[i] -= quantum
            curr += quantum
        else:
            curr += process.Bt.items[i]
            process.Bt.items[i] = 0
        cpro = process.queue.items[a]
        readyQueue(process, curr)
        if process.Bt.items[i] != 0:
            process.queue.items.pop(a)
            process.queue.enqueue(cpro)
        break
    return curr

# Function to perform Round Robin scheduling
def round_robin(process, num_process, quantum):
    copyAr = process.Ar.items.copy()
    copyAr1 = process.Ar.items.copy()
    copyAr.sort()
    copyBt1 = process.Bt.items.copy()
    copyBt2 = process.Bt.items.copy()

    curr = 0
    totalTat = 0
    totalWt = 0
    a = 0

    if copyAr[0] != 0:
        curr = copyAr[0]
        process.Ct.enqueue(copyAr[0])
        process.Pr.enqueue(f"//")
        readyQueue(process, curr)
    else:
        for i in range(num_process):
            if copyAr1[i] == 0:
                readyQueue(process, curr)
                curr = cbtProcess(process, curr, quantum, i, a)
                if process.Bt.items[i] == 0:
                    process.Ct.items[i] = curr
                    a += 1
                else:
                    process.Ct.enqueue(curr)
                    process.Pr.enqueue(process.Pr.items[i])

    while a < num_process:
        if curr < copyAr[a]:
            curr = copyAr[a]
            process.Ct.enqueue(copyAr[a])
            process.Pr.enqueue(f"//")
            readyQueue(process, curr)
            a -= 1
        else:
            for i in range(num_process):
                if process.queue.items[a] == process.Pr.items[i]:
                    curr = cbtProcess(process, curr, quantum, i, a)
                    readyQueue(process, curr)
                    if process.Bt.items[i] == 0:
                        process.Ct.items[i] = curr
                    else:
                        process.Ct.enqueue(curr)
                        process.Pr.enqueue(process.Pr.items[i])
                        a -= 1
                    break
        a += 1

    for i in range(num_process):
        process.Tat.items[i] = process.Ct.items[i] - process.Ar.items[i]
        process.Wt.items[i] = process.Tat.items[i] - copyBt2[i]

    totalTat = sum(process.Tat.items)
    totalWt = sum(process.Wt.items)

    AveTat = round(totalTat / num_process, 2)
    AveWt = round(totalWt / num_process, 2)

    return process.Ct.items, process.Tat.items, process.Wt.items, AveTat, AveWt

# Define MemoryManager class
class MemoryManager:
    def _init_(self, memory_blocks):
        self.original_memory = memory_blocks[:]  # Keep the original memory blocks
        self.first_fit_memory = memory_blocks[:]  # Copy of memory blocks for First Fit
        self.best_fit_memory = memory_blocks[:]  # Copy of memory blocks for Best Fit
        self.worst_fit_memory = memory_blocks[:]  # Copy of memory blocks for Worst Fit
    
    def first_fit(self, processes):
        first_fit_output = []
        for process in processes:
            allocated = False
            for i in range(len(self.first_fit_memory)):
                if self.first_fit_memory[i] >= process.get_size():  # Check if memory block is sufficient
                    allocated = True
                    x = self.first_fit_memory[i]  # Memory block before allocation
                    self.first_fit_memory[i] -= process.get_size()  # Allocate process
                    y = self.first_fit_memory[i]  # Memory block after allocation
                    first_fit_output.append(f"Process of size {process.get_size()} is allocated in the memory block of {x} and new hole is created of size {y}")
                    break
            if not allocated:
                first_fit_output.append(f"Process of size {process.get_size()} is not allocated because sufficient contiguous memory hole is not available to load the process.")
        return first_fit_output
    
    def best_fit(self, processes):
        best_fit_output = []
        for process in processes:
            index = -1
            for i in range(len(self.best_fit_memory)):
                if self.best_fit_memory[i] >= process.size:
                    if index == -1 or self.best_fit_memory[i] < self.best_fit_memory[index]:
                        index = i
            if index != -1:
                x = self.best_fit_memory[index]  # Memory block before allocation
                self.best_fit_memory[index] -= process.size  # Allocate process
                y = self.best_fit_memory[index]  # Memory block after allocation
                best_fit_output.append(f"Process of size {process.size} is allocated in the memory block of {x} and new hole is created of size {y}")
            else:
                best_fit_output.append(f"Process of size {process.size} is not allocated because sufficient contiguous memory hole is not available to load the process.")
        return best_fit_output
    
    def worst_fit(self, processes):
        worst_fit_output = []
        for process in processes:
            index = -1
            for i in range(len(self.worst_fit_memory)):
                if self.worst_fit_memory[i] >= process.size:
                    if index == -1 or self.worst_fit_memory[index] < self.worst_fit_memory[i]:
                        index = i
            if index != -1:
                x = self.worst_fit_memory[index]  # Memory block before allocation
                self.worst_fit_memory[index] -= process.size  # Allocate process
                y = self.worst_fit_memory[index]  # Memory block after allocation
                worst_fit_output.append(f"Process of size {process.size} is allocated in the memory block of {x} and new hole is created of size {y}")
            else:
                worst_fit_output.append(f"Process of size {process.size} is not allocated because sufficient contiguous memory hole is not available to load the process.")
        return worst_fit_output

def main():
    # Load data
    #data = pd.read_csv('data.csv')

    # Page layout
    st.set_page_config(layout="wide")

    # Sidebar
    st.sidebar.title('Select Section')
    radio_button_style = """
    <style>
    /* Add your CSS styles here */
    .radio-label {
        display: block;
        margin-bottom: 10px;
        cursor: pointer;
        font-size: 16px;
        color: #555;
    }

    .radio-input {
        position: absolute;
        opacity: 0;
    }

    .radio-custom {
        position: relative;
        display: inline-block;
        padding-left: 25px;
        margin-right: 10px;
        cursor: pointer;
    }

    .radio-custom:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 18px;
        height: 18px;
        border: 2px solid #007bff; /* Radio button border color */
        border-radius: 50%;
        background-color: #fff;
    }

    .radio-custom:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #007bff; /* Radio button color */
        opacity: 0;
        transition: opacity 0.2s;
    }

    .radio-input:checked + .radio-custom:after {
        opacity: 1;
    }

    .radio-label:hover .radio-custom:before {
        border-color: #007bff; /* Radio button border color on hover */
    }
    </style>
    """
    section = st.sidebar.radio('', ['Home','Commands','System calls', 'Scheduling Algorithm', 'Memory Management Algorithm','Diary Processing Plant'])

    # Home page
    if section == 'Home':
        st.markdown('<h1 style="color: black;">OS Case Study: Minix</h1>', unsafe_allow_html=True)
        st.write("""
        MINIX is a Unix-like operating system renowned for its microkernel architecture. Initially created by Andrew S. Tanenbaum, along with other contributors, MINIX has a rich history dating back to its first release in 1987. Here's a detailed description of MINIX:

        *Development and Purpose:*
        MINIX was initially developed by Andrew S. Tanenbaum at Vrije Universiteit in Amsterdam. The primary goal was to provide a practical example of the principles discussed in Tanenbaum's textbook "Operating Systems: Design and Implementation" (1987). Early versions of MINIX were specifically designed for educational purposes, aiming to teach students about operating system concepts and principles. They were system-call compatible with Seventh Edition Unix.

        *Architecture:*
        MINIX is based on a microkernel architecture, which distinguishes it from monolithic kernel-based operating systems like Linux. In a microkernel system, the kernel provides only essential functions, such as inter-process communication and memory management, while other services traditionally found in the kernel are implemented as user-space processes.

        *Evolution:*
        MINIX has undergone significant evolution over the years. While early versions focused on education, particularly with the release of MINIX 1.0, subsequent versions shifted towards reliability and self-healing capabilities. Starting with MINIX 3, the development emphasis shifted to creating a highly reliable and self-healing microkernel operating system suitable for embedded systems and other applications where reliability is critical.

        *Licensing:*
        Initially, MINIX was proprietary software, but it transitioned to open-source with the release of MINIX 3. The complete source code of MINIX has been available since its early days, making it a valuable resource for educational institutions and researchers. MINIX is licensed under the BSD-3-Clause license, allowing for flexibility in its usage and modification.

        *Features:*
        Since version 2.0, MINIX has been POSIX compliant, ensuring compatibility with a wide range of Unix-like systems and applications. MINIX supports various platforms, including IBM PC compatibles, 68000, SPARC, Atari ST, Amiga, Macintosh, SPARCstation, Intel 386, NS32532, ARM, Inmos transputer, and Intel Management Engine. It offers a package manager and supports updates by compiling from source code.

        *Community and Documentation:*
        The MINIX community consists of developers, educators, and enthusiasts who contribute to its development and use it for educational and practical purposes. Documentation, including manuals and tutorials, is available to aid users and developers in understanding and utilizing MINIX effectively.

        Overall, MINIX stands as a testament to the principles of operating system design and implementation, providing a robust platform for learning, research, and practical application in various computing environments. Its evolution from an educational tool to a reliable and versatile microkernel operating system reflects the adaptability and innovation characteristic of open-source software development.
        """)

        st.markdown('<h3 style="color: black;">Group Members:</h3>', unsafe_allow_html=True)
        st.markdown("""
        <table id="customers">
            <thead>
                <tr>
                    <th>Roll No:</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CB.EN.U4CSE22522</td>
                    <td>Kanishka</td>
                </tr>
                <tr>
                    <td>CB.EN.U4CSE22533</td>
                    <td>Nandini</td>
                </tr>
                <tr>
                    <td>CB.EN.U4CSE22543</td>
                    <td>Hansika</td>
                </tr>
                <tr>
                    <td>CB.EN.U4CSE22544</td>
                    <td>Shreya</td>
                </tr>
                <tr>
                    <td>CB.EN.U4CSE22546</td>
                    <td>Srinidhi</td>
                </tr>
            </tbody>
        </table>
        """, unsafe_allow_html=True)
        #st.dataframe(data)
    elif section == 'Commands':

        st.markdown('<h3 style="color: black;">MINIX:Commands.</h3>', unsafe_allow_html=True)
        st.write("""
       *1.Co:*
        The co command in MINIX is typically used for checking out files from a version control system like CVS (Concurrent Versions System).            
                 
       *2.Env:*
        The env command is used to display or modify the environment variables for a command.
                 
       *3.Finger:*
        The finger command is used to display information about users on a system, such as login name, full name, terminal, idle time, etc.
                 
       *4.Jot:*
        The jot command is used to generate sequences of numbers or strings.
                 
       *5.Lua:*
        The lua command is used to run Lua scripts or start an interactive Lua shell.
                 
       *6.Ps:*
        The ps command is used to display information about active processes running on the system, such as process ID (PID), terminal, CPU and memory usage, etc.
                 
       *7.crc:*
        The crc command is used to compute cyclic redundancy check (CRC) values for files.
                 
       *8.srccrc:*
        The srccrc command is used to compute CRC values for source files during compilation.
                 
       *9.sum:*
        The sum command is used to calculate a checksum (a simple hash) for a file.
                 
       *10.Tee:*
        The tee command is used to read from standard input and write to standard output and files simultaneously.
        """)


    
    elif section == 'System calls':

        st.markdown('<h3 style="color: black;">MINIX:System Calls.</h3>', unsafe_allow_html=True)
        st.write("""
       *System calls:*
        System calls are the interface between application programs and operating system. There are 53 main system calls in Minix.
        
        Some of them are listed below.
                 
        Related to Process Management:
        Fork system call:It creates a new process,child process, which is a
        exact copy of the calling process,parent process. But the child process will
        not inherit some properties from parent like child process has unique
        processId resource utilization
	    Wait System call:It causes the caller to wait until a signal is received 
        from the child process. It blocks the caller until the child process terminates.

        Related to File Management:
	    Access system call with parameters,name,amode.
        It is used to check whether the calling process is accessed by file path 
        according to modes. Here name is file name a mode is mode that specifies the 
        accessibility which is inclusive or of the bits R_OK,W_OK and W_OK.
        F_OK tests whether the file exists or not.
	    Rename system call with parameters,old,new:It is used to change the name of
        existing file. 
        It is also used to move a file between directories if required. 
        Here old is the previous name of the file, new is the changed name of the file.
	    open system call with parameters,file,how,and many:open and possibly create a 
        file or device.
        These request opening the file read-only,write-only,or read/write,respectively.

        Related to Directory & File System Management:
	    Creat system call with parameters,name,mode:The create system call is used to 
        create a new empty file.
        We can specify the permission and the name of the file which we want to 
        create using this.
	    chdir system call with parameters,dest:The chdir command enables you to change
        the current working directory on the remote server.
        When used with no parameter, this command prints the current directory,
        which is the same behavior as the pwd command.
        """)

    # Scheduling Algorithm
    elif section == 'Scheduling Algorithm':
        st.markdown('<h2 style="color: black;">Scheduling Algorithm: Round Robin</h2>', unsafe_allow_html=True)
        st.write('Insert the input parameters for the scheduling algorithm:')
        num_process = st.number_input('Number of Processes:', min_value=1, step=1)
        quantum = st.number_input('Quantum:', min_value=1, step=1)
        
        
        arrival_times = []
        burst_times = []
        for i in range(num_process):
            arrival_time = st.number_input(f"Enter P{i+1} Arrival Time:", min_value=0, key=f"arrival_time_{i}")
            burst_time = st.number_input(f"Enter P{i+1} Burst Time:", min_value=1, key=f"burst_time_{i}")
            arrival_times.append(arrival_time)
            burst_times.append(burst_time)

        if st.button('Run Round Robin Algorithm'):
            process = Process1()
            for i in range(num_process):
                process.Pr.enqueue(f"P{i+1}")
                process.Ct.enqueue(i)
                process.Tat.enqueue(i)
                process.Wt.enqueue(i)
                process.Ar.enqueue(arrival_times[i])
                process.Bt.enqueue(burst_times[i])

            ct, tat, wt, ave_tat, ave_wt = round_robin(process, num_process, quantum)

            st.write("Results:")
            st.write("P|AT|BT|CT|TAT|WT")
            for i in range(num_process):
                st.write(f"{process.Pr.items[i]}\t|\t{process.Ar.items[i]}\t|\t{process.Bt.items[i]}\t|\t{ct[i]}\t|\t{tat[i]}\t|\t{wt[i]}")

            st.write("Total TAT:", sum(tat), "ms")
            st.write("Total WT:", sum(wt), "ms")
            st.write("Average TAT:", ave_tat, "ms")
            st.write("Average WT:", ave_wt, "ms")

            # Print Gantt Chart
            st.write("\nGantt Chart:")
            gantt_chart = ""
            for i in range(len(process.Ct.items)):
                gantt_chart += f"{process.Pr.items[i]} | "
            st.write(gantt_chart[:-3])

    # Memory Management Algorithm
    elif section == 'Memory Management Algorithm':
        st.markdown('<h2 style="color: black;">Memory Management Algorithm</h2>', unsafe_allow_html=True)
        st.write('Insert your memory management algorithm code here.')
        algo_choice = st.selectbox('Select Memory Management Algorithm', ['First Fit', 'Best Fit', 'Worst Fit'])

        # Input for memory blocks
        num_memory_blocks = st.number_input("Enter the number of memory blocks:", min_value=1, step=1)
        memory_blocks = []
        for i in range(num_memory_blocks):
            size = st.number_input(f"Enter size of memory block {i + 1}:", min_value=1, step=1)
            memory_blocks.append(size)

        # Input for