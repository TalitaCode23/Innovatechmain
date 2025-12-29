document.querySelectorAll('.aprovar').forEach(select => {
    select.addEventListener('change', () => {

        if (!select.value) return;

        fetch('../Shared/aprovar_usuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: select.dataset.user,
                role: select.value
            })
        })
        .then(res => {
            if (res.ok) {
                select.closest('tr').remove();
            } else {
                alert('Erro ao aprovar usuário');
            }
        });
    });
});
