import { replaceUrlWithoutReload } from '@/utils/route';
import { produce } from 'immer';
import { create } from 'zustand';

const useTaskDrawerStore = create((set, get) => ({
  create: {
    opened: false,
    group_id: null,
  },
  edit: {
    opened: false,
    task: {},
    // inPlace: drawer dibuka di atas halaman list (URL tidak diubah ke halaman kanban)
    inPlace: false,
    // context: data per periode (status, pengguna) bila halaman tidak memilikinya di props
    context: null,
  },
  openCreateTask: (groupId = null) => {
    return set(produce(state => {
      state.create.opened = true;
      state.create.group_id = groupId;
    }));
  },
  closeCreateTask: () => {
    return set(produce(state => {
      state.create.opened = false;
      state.create.group_id = null;
    }));
  },
  openEditTask: (task, options = {}) => {
    const { inPlace = false, context = null } = options;

    if (!inPlace) {
      replaceUrlWithoutReload(
        route("projects.tasks.open", [task.project_id, task.id])
      );
    }

    return set(produce(state => {
      state.edit.opened = true;
      state.edit.task = task;
      state.edit.inPlace = inPlace;
      state.edit.context = context;
    }));
  },
  closeEditTask: () => {
    if (!get().edit.inPlace) {
      replaceUrlWithoutReload(route("projects.tasks", get().edit.task.project_id));
    }

    return set(produce(state => {
      state.edit.opened = false;
      state.edit.inPlace = false;
      state.edit.context = null;
    }));
  },
}));

export default useTaskDrawerStore;
