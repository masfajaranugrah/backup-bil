@php
    $unreadInformationCount = $unreadInformationCount ?? session('customer_unread_information_count', 0);
@endphp

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a class="tab-btn {{ $active === 'home' ? 'active' : '' }}" href="/dashboard/customer/tagihan/home">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </a>

    <a class="tab-btn {{ $active === 'tagihan' ? 'active' : '' }}" href="/dashboard/customer/tagihan">
        <i class="bi bi-receipt"></i>
        <span>Tagihan</span>
    </a>

    <a class="tab-btn {{ $active === 'invoice' ? 'active' : '' }}" href="/dashboard/customer/tagihan/selesai">
        <i class="bi bi-file-earmark-text"></i>
        <span>Kwitansi</span>
    </a>

    <a class="tab-btn {{ $active === 'informasi' ? 'active' : '' }}" href="/dashboard/customer/informasi">
        <i class="bi bi-megaphone"></i>
        @if($unreadInformationCount > 0 && $active !== 'informasi')
            <span class="notification-count" aria-label="{{ $unreadInformationCount }} informasi baru">{{ $unreadInformationCount > 99 ? '99+' : $unreadInformationCount }}</span>
        @endif
        <span>Info</span>
    </a>

    <a class="tab-btn {{ $active === 'chat' ? 'active' : '' }}" href="/dashboard/customer/chat">
        <i class="bi bi-chat-dots"></i>
        <span>Chat</span>
    </a>

    <a class="tab-btn {{ $active === 'profile' ? 'active' : '' }}" href="/dashboard/customer/profile">
        <i class="bi bi-person-circle"></i>
        <span>Profile</span>
    </a>
</div>

<style>
    /* Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    /* Bottom Navbar */
    .bottom-nav {
        position: fixed;
        bottom: calc(10px + env(safe-area-inset-bottom));
        left: 50%;
        transform: translateX(-50%);
        width: min(92vw, 420px);
        height: 64px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.62), rgba(255, 255, 255, 0.28));
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 8px 16px;
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.24), 0 6px 18px rgba(15, 23, 42, 0.10), inset 0 1px 0 rgba(255, 255, 255, 0.95), inset 0 -1px 0 rgba(255, 255, 255, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.76);
        border-radius: 9999px;
        z-index: 999;
        font-family: 'Inter', sans-serif;
        backdrop-filter: blur(30px) saturate(220%) contrast(112%);
        -webkit-backdrop-filter: blur(30px) saturate(220%) contrast(112%);
        overflow: hidden;
        animation: glassFloat 4.5s ease-in-out infinite;
    }

    .bottom-nav::before {
        content: '';
        position: absolute;
        inset: -40% -30%;
        border-radius: inherit;
        background: linear-gradient(115deg, transparent 18%, rgba(255, 255, 255, 0.72) 34%, rgba(255, 255, 255, 0.18) 45%, transparent 62%);
        pointer-events: none;
        z-index: 0;
        transform: translateX(-42%) rotate(8deg);
        animation: glassShimmer 3.2s ease-in-out infinite;
    }

    .bottom-nav::after {
        content: '';
        position: absolute;
        inset: 1px;
        border-radius: inherit;
        border: 1px solid rgba(255, 255, 255, 0.36);
        pointer-events: none;
        z-index: 0;
    }

    .bottom-nav .tab-btn {
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(15, 23, 42, 0.58);
        position: relative;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 44px;
        height: 44px;
        padding: 0;
        border-radius: 9999px;
        line-height: 0;
        z-index: 1;
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }

    .bottom-nav .tab-btn:hover {
        background: rgba(255, 255, 255, 0.52);
    }

    .bottom-nav .tab-btn i {
        font-size: 1.35rem;
        line-height: 1;
        display: block;
        margin: 0;
        padding: 0;
    }

    .bottom-nav .tab-btn span {
        display: none;
    }

    .bottom-nav .tab-btn .notification-count {
        display: block;
        position: absolute;
        top: 3px;
        right: 1px;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        border-radius: 999px;
        background: #ef4444;
        border: 2px solid #1f2326;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.18);
        color: #ffffff;
        font-size: 0.62rem;
        font-weight: 900;
        line-height: 13px;
        text-align: center;
    }

    .bottom-nav .tab-btn.active .notification-count {
        border-color: #ffffff;
    }

    /* Active tab */
    .bottom-nav .tab-btn.active {
        width: 48px;
        height: 48px;
        color: #0f172a;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.68));
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.20), inset 0 1px 0 rgba(255, 255, 255, 1), inset 0 -1px 0 rgba(15, 23, 42, 0.06);
        line-height: 0;
        animation: glassActivePop 0.28s ease-out;
    }

    .bottom-nav .tab-btn.active::before {
        content: none;
    }

    @keyframes glassShimmer {
        0%, 42% {
            transform: translateX(-48%) rotate(8deg);
            opacity: 0;
        }
        56% {
            opacity: 0.75;
        }
        100% {
            transform: translateX(48%) rotate(8deg);
            opacity: 0;
        }
    }

    @keyframes glassFloat {
        0%, 100% {
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.24), 0 6px 18px rgba(15, 23, 42, 0.10), inset 0 1px 0 rgba(255, 255, 255, 0.95), inset 0 -1px 0 rgba(255, 255, 255, 0.28);
        }
        50% {
            box-shadow: 0 26px 56px rgba(15, 23, 42, 0.28), 0 8px 22px rgba(15, 23, 42, 0.12), inset 0 1px 0 rgba(255, 255, 255, 1), inset 0 -1px 0 rgba(255, 255, 255, 0.36);
        }
    }

    @keyframes glassActivePop {
        0% {
            transform: scale(0.88);
        }
        70% {
            transform: scale(1.06);
        }
        100% {
            transform: scale(1);
        }
    }

</style>
