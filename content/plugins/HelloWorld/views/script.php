<script>
    fetch("<?= $data['api'] ?>").then(res => res.json()).then(res => {
        document.querySelector('#chickenSoup').innerText = res;
    });
</script>
