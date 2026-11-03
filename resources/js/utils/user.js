export const getInitials = (name) => {
    if (!name.includes(" ")) {
        return name.slice(0, 2).toUpperCase();
    }
    const [firstname, lastname] = name.split(" ");

    if (!lastname) {
        return firstname.slice(0, 2).toUpperCase();
    }

    return (firstname[0] + lastname[0]).toUpperCase();
};

export const shortName = (name) => {
  if (!name.includes(" ")) {
    return name;
  }
  const [firstname, lastname] = name.split(" ");

  return firstname + ' ' + lastname[0] + '.';
};

export const hasRoles = (user, roles) => {
  // auth.user.roles berupa daftar nama ("admin"), sedangkan user lain berupa objek ({ name })
  return (user.roles || []).some((i) => roles.includes(typeof i === "string" ? i : i?.name));
};

/**
 * Yang boleh mengedit sebuah pelayanan: administrator, penerima pelayanan (pembuat),
 * dan penerima tugas (yang ditugaskan).
 */
export const canEditTaskItem = (user, task) => {
  if (!user || !task) return false;

  return (
    hasRoles(user, ["admin"]) ||
    user.id === task.created_by_user_id ||
    user.id === task.assigned_to_user_id
  );
};
