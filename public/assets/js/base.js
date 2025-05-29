

function user_name_badge(username, role_type, active) {
    if (active == 1) {
        if (role_type == "admin") {
            return `<span class="fw-bold text-primary"><i class="bi bi-person-fill-gear"></i> ${username}</span>`;
        } else if (role_type == "standard") {
            return `<span class="fw-bold text-success"><i class="bi bi-person-fill"></i> ${username}</span>`;
        } else if (role_type == "employee") {
            return `<span class="fw-bold text-dark"><i class="bi bi-person-lines-fill"></i> ${username}</span>`;
        }  
    } else if (active == 0) {
        if (role_type == "admin") {
            return `<span class="fw-bold text-danger"><i class="bi bi-person-fill-gear"></i> ${username}</span>`;
        } else if (role_type == "standard") {
            return `<span class="fw-bold text-danger"><i class="bi bi-person-fill"></i> ${username}</span>`;
        } else if (role_type == "employee") {
            return `<span class="fw-bold text-danger"><i class="bi bi-person-lines-fill"></i> ${username}</span>`;
        }
    }
}

function user_role_badge(username, role_type, active) {
    if (role_type == "admin" && active == 1) {
        return `<span class="badge bg-primary">Admin</span>`;
    } else if (role_type == "admin" && active == 0) {
        return `<span class="badge bg-danger">Admin</span>`;
    } else if (role_type == "standard" && active == 1) {
        return `<span class="badge bg-success">Standard</span>`;
    } else if (role_type == "standard" && active == 0) {
        return `<span class="badge bg-secondary">Standard</span>`;
    } else if (role_type == "employee" && active == 1) {
        return `<span class="badge bg-dark">Employee</span>`;
    } else if (role_type == "employee" && active == 0) {
        return `<span class="badge bg-light">Employee</span>`;
    }

}