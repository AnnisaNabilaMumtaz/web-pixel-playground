document.addEventListener("DOMContentLoaded", () => {
    let communities = [
        {
            id: 1,
            name: "Minecraft Indonesia",
            description: "Komunitas resmi pecinta Minecraft di Indonesia.",
            image: "../assets/img/community-1.jpg",
            discordLink: "https://discord.gg/minecraft-indonesia",
            createdAt: "10/10/24",
        },
    ];

    const tableBody = document.querySelector("tbody");

    // Render komunitas ke tabel
    const renderCommunities = () => {
        tableBody.innerHTML = "";
        communities.forEach((community, index) => {
            tableBody.innerHTML += `
                <tr data-id="${community.id}">
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div>
                                <img src="${community.image}" class="avatar avatar-sm me-3" alt="community">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${community.name}</h6>
                                <p class="text-xs text-secondary mb-0">Komunitas</p>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">${community.createdAt}</span>
                    </td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-info me-2" onclick="showDetails(${index})">
                            <i class="fas fa-eye me-1"></i>Detail
                        </button>
                        <button class="btn btn-sm btn-warning me-2" onclick="editCommunity(${index})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCommunity(${index})">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </td>
                </tr>
            `;
        });
    };

    // Fungsi untuk menampilkan detail komunitas
    const showDetails = (index) => {
        const community = communities[index];
        const modal = new bootstrap.Modal(document.getElementById("showCommunityDetailsModal"));

        document.querySelector("#showCommunityDetailsModal .modal-title").innerText = community.name;
        document.querySelector("#showCommunityDetailsModal input[name='name']").value = community.name;
        document.querySelector("#showCommunityDetailsModal textarea[name='description']").value = community.description;
        document.querySelector("#showCommunityDetailsModal input[name='discordLink']").value = community.discordLink;
        document.querySelector("#showCommunityDetailsModal img").src = community.image;

        modal.show();
    };

    // Fungsi untuk mengedit komunitas
    const editCommunity = (index) => {
        const community = communities[index];
        const modal = new bootstrap.Modal(document.getElementById("editCommunityModal"));

        document.getElementById("editCommunityName").value = community.name;
        document.getElementById("editDescription").value = community.description;
        document.getElementById("editDiscordLink").value = community.discordLink;
        document.getElementById("editImagePreviewContainer").innerHTML = `
            <img src="${community.image}" class="img-thumbnail" style="max-width: 150px;" alt="Current Image">
        `;

        const saveButton = document.querySelector("#editCommunityModal .btn-primary");
        saveButton.onclick = () => {
            community.name = document.getElementById("editCommunityName").value;
            community.description = document.getElementById("editDescription").value;
            community.discordLink = document.getElementById("editDiscordLink").value;

            const newImageFile = document.getElementById("editCommunityImage").files[0];
            if (newImageFile) {
                community.image = URL.createObjectURL(newImageFile);
            }

            renderCommunities();
            modal.hide();
        };

        modal.show();
    };

    // Fungsi untuk menghapus komunitas
    const deleteCommunity = (index) => {
        const modal = new bootstrap.Modal(document.getElementById("deleteCommunityModal"));

        document.querySelector("#deleteCommunityModal .modal-body strong").innerText = communities[index].name;

        const deleteButton = document.querySelector("#deleteCommunityModal .btn-danger");
        deleteButton.onclick = () => {
            communities.splice(index, 1);
            renderCommunities();
            modal.hide();
        };

        modal.show();
    };

    // Tambah komunitas
    const addCommunity = () => {
        const name = document.getElementById("communityName").value;
        const description = document.getElementById("description").value;
        const imageFile = document.getElementById("communityImage").files[0];
        const image = imageFile
            ? URL.createObjectURL(imageFile)
            : "../assets/img/default-image.jpg";
        const discordLink = document.getElementById("discordLink").value;

        communities.push({
            id: Date.now(),
            name,
            description,
            image,
            discordLink,
            createdAt: new Date().toLocaleDateString(),
        });

        renderCommunities();
        document.getElementById("addCommunityModal").querySelector(".btn-close").click();
    };

    // Event listener untuk tombol tambah komunitas
    document.querySelector("#addCommunityModal .btn-primary").onclick = addCommunity;

    // Event listener untuk membuka modal tambah komunitas
    document.querySelector("#addCommunityModal").addEventListener("show.bs.modal", () => {
        // Reset kolom input ketika modal ditampilkan
        document.getElementById("communityName").value = "";
        document.getElementById("description").value = "";
        document.getElementById("communityImage").value = ""; // Reset gambar
        document.getElementById("discordLink").value = "";
    });

    renderCommunities();

    window.showDetails = showDetails;
    window.editCommunity = editCommunity;
    window.deleteCommunity = deleteCommunity;
});
