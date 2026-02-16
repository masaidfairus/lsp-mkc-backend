document.querySelectorAll('form[data-action]').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const action = form.dataset.action;
        const method = form.dataset.method || 'POST';

        const data = {};
        form.querySelectorAll('input[name]').forEach(input => {
            data[input.name] = input.value;
        });

        try {
            const res = await fetch(action, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await res.json();

            if (!res.ok) {
                console.error(result);
                alert(result.message || 'Gagal');
                return;
            }

            // REGISTER
            if (action.includes('register')) {
                alert('Register berhasil, cek email!');
                window.location.href = '/signin';
            }

            // LOGIN
            if (action.includes('login')) {
                localStorage.setItem('token', result.token);
                window.location.href = '/dashboard';
            }

        } catch (err) {
            console.error(err);
            alert('Server error');
        }
    });
});
