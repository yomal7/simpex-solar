customers.forEach(customer => {
    const tr = document.createElement('tr');
    const trContent = `
        <td><img src="${customer.image}" alt="${customer.name}" class="profile-image"></td>
        <td>${customer.name}</td>
        <td>${customer.status}</td>
        <td class="primary"><button class="button-28" role="button">View</button></td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});
