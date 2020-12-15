<div class="menu">
    <ul>
        <li class="menu__item"><a href="/dashboard" class="menu__item__link {{Request::is('dashboard*') ? 'menu__item__link--active' : ''}}">Dashboard</a></li>
        <li class="menu__item"><a href="/links" class="menu__item__link {{Request::is('links*') ? 'menu__item__link--active' : ''}}">Links</a></li>
        <li class="menu__item"><a href="/indexer" class="menu__item__link {{Request::is('indexer*') ? 'menu__item__link--active' : ''}}">Indexer</a></li>
    </ul>
</div>