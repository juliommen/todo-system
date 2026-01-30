import type { PayloadAction } from "@reduxjs/toolkit";
import { createAsyncThunk, createSlice } from "@reduxjs/toolkit";
import * as api from "@/features/tasks";
import type { Task, TaskStatus } from "@/features/tasks";

type TasksState = {
  items: Task[];
  loading: boolean;
  error?: string | null;
};

const initialState: TasksState = {
  items: [],
  loading: false,
  error: null,
};

export const fetchTasks = createAsyncThunk("tasks/fetch", async () => {
  return await api.fetchTasksAPI();
});

export const createTask = createAsyncThunk(
  "tasks/create",
  async (payload: { title: string; description: string }) => {
    return await api.createTaskAPI(payload);
  },
);

export const updateTaskStatus = createAsyncThunk(
  "tasks/updateStatus",
  async ({ id, status }: { id: string; status: TaskStatus }) => {
    return await api.updateTaskStatusAPI(id, status);
  },
);

export const deleteTask = createAsyncThunk(
  "tasks/delete",
  async (id: string) => {
    await api.deleteTaskAPI(id);
    return id;
  },
);

export const taskSlice = createSlice({
  name: "tasks",
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchTasks.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchTasks.fulfilled, (state, action: PayloadAction<Task[]>) => {
        state.items = action.payload;
        state.loading = false;
      })
      .addCase(fetchTasks.rejected, (state) => {
        state.loading = false;
        state.error = "Erro ao obter tarefas";
      })

      .addCase(createTask.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(createTask.fulfilled, (state, action: PayloadAction<Task>) => {
        state.loading = false;
        state.items.unshift(action.payload);
      })
      .addCase(createTask.rejected, (state) => {
        state.loading = false;
        state.error = "Erro ao gravar tarefa";
      })

      .addCase(updateTaskStatus.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(
        updateTaskStatus.fulfilled,
        (state, action: PayloadAction<Task>) => {
          state.loading = false;
          state.items = state.items.map((t) =>
            t.id === action.payload.id ? action.payload : t,
          );
        },
      )
      .addCase(updateTaskStatus.rejected, (state) => {
        state.loading = false;
        state.error = "Erro ao atualizar tarefa";
      })

      .addCase(deleteTask.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(deleteTask.fulfilled, (state, action: PayloadAction<string>) => {
        state.loading = false;
        state.items = state.items.filter((t) => t.id !== action.payload);
      })
      .addCase(deleteTask.rejected, (state) => {
        state.loading = false;
        state.error = "Erro ao excluir tarefa";
      });
  },
});
