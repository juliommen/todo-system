import { TasksManager } from "@/pages/TasksManager";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";

export function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<TasksManager />} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
}
