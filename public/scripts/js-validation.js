const emailRegex = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
const nameRegex = /^[a-zA-Z.\s]+$/;

function showError(id, message) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerText = message;
}
function clearErrors() {
    document.querySelectorAll('.js-error').forEach(el => {
        el.innerText = '';
        el.style.color = '';
    });
}
function validateFields(rules) {
    let isValid = true;
    clearErrors();

    for (const rule of rules) {
        const value = rule.getValue();

        if (rule.required && !value) {
            showError(rule.errorId, rule.requiredMsg);
            isValid = false;
            continue;
        }

        if (rule.min && value.length < rule.min) {
            showError(rule.errorId, rule.minMsg);
            isValid = false;
            continue;
        }

        if (rule.pattern && value && !rule.pattern.test(value)) {
            showError(rule.errorId, rule.patternMsg);
            isValid = false;
            continue;
        }

        if (rule.compare && value !== rule.compare()) {
            showError(rule.errorId, rule.compareMsg);
            isValid = false;
        }
    }

    return isValid;
}
function setFieldMessage(fieldId, message, isError) {
    const target = document.getElementById(fieldId);
    if (!target) return;
    target.innerText = message;
    target.style.color = isError ? '#d93025' : '#0f9d58';
}
async function checkFieldAvailability(type, value, fieldId) {
    if (!value) {
        setFieldMessage(fieldId, '', true);
        return;
    }

    const params = new URLSearchParams({
        action: 'check_availability',
        type,
        value
    });

    try {
        const response = await fetch(`${window.APP_BASE_URL}/controllers/auth_controller.php?${params}`);
        if (!response.ok) {
            setFieldMessage(fieldId, 'Could not verify availability.', true);
            return;
        }

        const data = await response.json();
        setFieldMessage(fieldId, data.message, !data.available);
    } catch (error) {
        setFieldMessage(fieldId, 'Network error. Please try again.', true);
    }
}
function validateRegisterForm() {
    return validateFields([
        {
            errorId: 'nameError',
            required: true,
            requiredMsg: 'Name is required.',
            min: 3,
            minMsg: 'Name must be at least 3 characters.',
            pattern: nameRegex,
            patternMsg: 'Only letters, spaces, and dots allowed.',
            getValue: () => document.getElementById('name')?.value.trim() || ''
        },
        {
            errorId: 'emailError',
            required: true,
            requiredMsg: 'Email is required.',
            pattern: emailRegex,
            patternMsg: 'Enter a valid email address.',
            getValue: () => document.getElementById('email')?.value.trim() || ''
        },
        {
            errorId: 'passwordError',
            required: true,
            requiredMsg: 'Password is required.',
            min: 8,
            minMsg: 'Password must be at least 8 characters.',
            getValue: () => document.getElementById('password')?.value || ''
        },
        {
            errorId: 'confirmError',
            compare: () => document.getElementById('password')?.value || '',
            compareMsg: 'Passwords do not match.',
            getValue: () => document.getElementById('confirm')?.value || ''
        }
    ]);
}
function validateProfileForm() {
    const isValid = validateFields([
        {
            errorId: 'pnameError',
            required: true,
            requiredMsg: 'Name is required.',
            min: 2,
            minMsg: 'Name must be at least 2 characters.',
            getValue: () => document.getElementById('pname')?.value.trim() || ''
        },
        {
            errorId: 'pemailError',
            required: true,
            requiredMsg: 'Email is required.',
            pattern: emailRegex,
            patternMsg: 'Enter a valid email address.',
            getValue: () => document.getElementById('pemail')?.value.trim() || ''
        }
    ]);

    if (!isValid) {
        return false;
    }
    const pic = document.getElementById('profile_picture');
    if (pic && pic.files.length > 0) {
        const file = pic.files[0];
        const maxSize = 2 * 1024 * 1024;
        const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (file.size > maxSize) {
            showError('picError', 'Image must be under 2MB.');
            return false;
        }
        if (!allowed.includes(file.type)) {
            showError('picError', 'Only JPEG, PNG, GIF, WEBP allowed.');
            return false;
        }
    }

    return true;
}
function validatePasswordForm() {
    return validateFields([
        {
            errorId: 'newPassError',
            required: true,
            requiredMsg: 'New password is required.',
            min: 8,
            minMsg: 'Password must be at least 8 characters.',
            getValue: () => document.getElementById('new_password')?.value || ''
        },
        {
            errorId: 'confirmPassError',
            compare: () => document.getElementById('new_password')?.value || '',
            compareMsg: 'Passwords do not match.',
            getValue: () => document.getElementById('confirm_password')?.value || ''
        }
    ]);
}
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-pw').forEach(function (icon) {
        icon.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input    = document.getElementById(targetId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');

    if (nameInput) {
        nameInput.addEventListener('input', debounce(function () {
            const value = this.value.trim();
            if (value.length >= 3) {
                checkFieldAvailability('name', value, 'nameError');
            } else {
                setFieldMessage('nameError', '', true);
            }
        }, 500));
    }

    if (emailInput) {
        emailInput.addEventListener('input', debounce(function () {
            const value = this.value.trim();
            if (emailRegex.test(value)) {
                checkFieldAvailability('email', value, 'emailError');
            } else {
                setFieldMessage('emailError', '', true);
            }
        }, 500));
    }
});
