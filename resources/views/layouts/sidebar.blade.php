<div class="profile-header text-center position-relative" style="width: 100%; max-width: 140px; margin: 0 auto;">
    <form id="photo-form" action="{{ route('profile.upload.photo') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="profile-photo" class="position-relative d-inline-block">
            <img
                src="{{ Auth::user()->profile_photo
                    ? asset('storage/' . Auth::user()->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                class="rounded-circle mb-2"
                width="100"
                height="100"
                style="object-fit: cover; border: 3px solid white;"
                alt="Foto Profil"
            >

            <span class="camera-icon">
                <i class="fa fa-camera"></i>
            </span>
        </label>

        <input
            type="file"
            name="profile_photo"
            id="profile-photo"
            accept="image/*"
            style="display: none;"
            onchange="document.getElementById('photo-form').submit();"
        >
    </form>

    <h5 class="mt-2 mb-0">{{ Auth::user()->name }}</h5>
    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
</div>

<ul class="profile-menu mt-4">
    <li class="{{ request()->routeIs('profile.index') || request()->routeIs('profile.edit') ? 'active' : '' }}">
        <a href="{{ route('profile.index') }}">
            <i class="fa fa-user-circle me-2 fa-fw"></i> Profile
        </a>
    </li>

    <li class="{{ request()->routeIs('profile.orders') ? 'active' : '' }}">
        <a href="{{ route('profile.orders') }}">
            <i class="fa fa-shopping-bag me-2 fa-fw"></i> Riwayat Pesanan
        </a>
    </li>

    <li class="{{ request()->routeIs('profile.likes') ? 'active' : '' }}">
        <a href="{{ route('profile.likes') }}">
            <i class="fa fa-heart me-2 fa-fw"></i> Produk Disukai
        </a>
    </li>

    <li class="{{ request()->routeIs('profile.password') ? 'active' : '' }}">
        <a href="{{ route('profile.password') }}">
            <i class="fa fa-lock me-3 fa-fw"></i> Edit Password
        </a>
    </li>
</ul>
