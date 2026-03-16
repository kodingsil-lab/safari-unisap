<?php if ($pager->getPageCount() > 1): ?>
<style>
    .admin-pagination .pagination {
        gap: .45rem;
        flex-wrap: wrap;
    }
    .admin-pagination .page-link {
        min-width: 42px;
        height: 42px;
        padding: 0 .9rem;
        border-radius: 999px;
        border: 1px solid #dbe4f0;
        background: #fff;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: none;
    }
    .admin-pagination .page-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e3a8a;
    }
    .admin-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #1f3b8a, #0f172a);
        border-color: #1f3b8a;
        color: #fff;
    }
    .admin-pagination .page-item.disabled .page-link {
        color: #94a3b8;
        background: #f8fafc;
        border-color: #e2e8f0;
    }
</style>
<nav class="admin-pagination d-flex justify-content-center" aria-label="Navigasi halaman">
    <ul class="pagination mb-0">
        <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->hasPrevious() ? $pager->getPrevious() : '#' ?>" aria-label="Halaman sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
            </li>
        <?php endforeach ?>

        <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->hasNext() ? $pager->getNext() : '#' ?>" aria-label="Halaman berikutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>
<?php endif ?>
