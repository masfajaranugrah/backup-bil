@php
    $isFooter = false;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Chat Admin')

@use('Illuminate\Support\Facades\Auth')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('vendor-script')
    @vite(['resources/js/bootstrap.js', 'resources/js/echo.js'])
@endsection

@section('page-style')
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .admin-chat-container {
            display: flex;
            width: 100%;
            height: calc(100vh - 120px);
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 20px auto;
            max-width: 1400px;
            border: 1px solid #e5e7eb;
        }

        .users-sidebar {
            width: 392px;
            border-right: 1px solid #dbe4f0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 58%, #eef2ff 100%);
        }

        .sidebar-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 28px 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.08);
        }

        .admin-avatar {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 12px;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
        }

        .admin-info h2 {
            font-size: 20px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #ffffff;
        }

        .admin-status {
            font-size: 13px;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .search-box {
            padding: 14px 14px 10px;
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-switcher {
            display: flex;
            gap: 8px;
            padding: 12px 14px;
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-button {
            flex: 1;
            border: 1px solid #dbe4f0;
            background: #ffffff;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            padding: 9px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24);
        }

        .search-wrapper {
            position: relative;
        }

        .chat-search-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1px solid #dbe4f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
            background: #ffffff;
            color: #1e293b;
        }

        .chat-search-input::placeholder {
            color: #94a3b8;
        }

        .chat-search-input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .user-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px 10px 14px;
            background: transparent;
        }

        .user-list::-webkit-scrollbar {
            width: 6px;
        }

        .user-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .user-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .user-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .user-item {
            padding: 13px 12px;
            margin-bottom: 8px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .user-item:hover {
            background: #f8fbff;
            border-color: #cbd5e1;
            transform: translateX(3px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .user-item.active {
            background: #eff6ff;
            border-color: #93c5fd;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.16);
        }

        .user-item-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 700;
            margin-bottom: 3px;
            font-size: 15px;
            color: #1e293b;
        }

        .user-type {
            font-size: 12px;
            color: #64748b;
        }

        .user-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            min-width: 0;
        }

        .user-item.pinned {
            border-color: rgba(250, 204, 21, 0.82);
            background: linear-gradient(135deg, #fffbeb 0%, #eff6ff 100%);
        }

        .user-item.pinned .user-avatar {
            background: linear-gradient(135deg, #facc15, #f97316);
            color: #111827;
        }

        .pin-badge {
            display: none;
            align-items: center;
            gap: 4px;
            width: fit-content;
            border-radius: 999px;
            padding: 3px 8px;
            margin-top: 6px;
            background: #fef3c7;
            color: #92400e;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .user-item.pinned .pin-badge {
            display: inline-flex;
        }

        .pin-chat-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dbe4f0;
            border-radius: 11px;
            background: #ffffff;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .pin-chat-btn:hover {
            color: #92400e;
            border-color: #facc15;
            background: #fffbeb;
        }

        .pin-chat-btn.is-pinned {
            color: #111827;
            background: #facc15;
            border-color: #facc15;
        }

        .unread-badge {
            background: #ef4444;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
            padding: 5px 7px;
            border-radius: 999px;
            min-width: 22px;
            text-align: center;
            box-shadow: none;
            transition: none;
        }

        .chat-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        .chat-header {
            background: #ffffff;
            color: #1e293b;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #ffffff;
        }

        .chat-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
            color: #1e293b;
        }

        .chat-subtitle {
            font-size: 12px;
            color: #10b981;
            font-weight: 500;
        }

        .chat-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: #f8fafc;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .message {
            margin-bottom: 16px;
            display: flex;
            align-items: flex-end;
            gap: 10px;
            animation: slideIn 0.2s ease;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #ffffff;
            font-weight: 600;
            flex-shrink: 0;
        }

        .message.sent .message-avatar {
            background: #3b82f6;
            order: 2;
        }

        .message.received .message-avatar {
            background: #10b981;
        }

        .message-bubble {
            max-width: 60%;
        }

        .message-content {
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
        }

        .message.sent .message-content {
            background: #3b82f6;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .message.received .message-content {
            background: #ffffff;
            color: #1e293b;
            border-bottom-left-radius: 4px;
            border: 1px solid #e5e7eb;
        }

        .message-text {
            font-size: 14px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .message-info {
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .message.sent .message-info {
            justify-content: flex-end;
            color: rgba(255, 255, 255, 0.8);
        }

        .message.received .message-info {
            color: #94a3b8;
        }

        .message-status-wrap {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .message-text-deleted {
            font-style: italic;
            opacity: 0.88;
        }

        .message-edited {
            font-size: 10px;
            opacity: 0.9;
            margin-left: 4px;
        }

        .message-actions {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 8px;
        }

        .message-action-btn {
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 6px;
            background: rgba(148, 163, 184, 0.2);
            color: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .message-action-btn:hover {
            background: rgba(15, 23, 42, 0.2);
            transform: translateY(-1px);
        }

        .message-highlight .message-bubble {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.28), 0 18px 40px rgba(15, 23, 42, 0.18);
        }

        .message-reply-preview {
            border-left: 4px solid rgba(15, 23, 42, 0.35);
            background: rgba(255, 255, 255, 0.16);
            border-radius: 10px;
            padding: 8px 10px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        .message.received .message-reply-preview {
            background: #f1f5f9;
            border-left-color: #64748b;
        }

        .reply-preview-name {
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .reply-preview-text {
            font-size: 13px;
            opacity: 0.86;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .reply-compose-preview {
            display: flex;
            align-items: stretch;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #111827;
            border-radius: 14px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .reply-compose-body {
            flex: 1;
            min-width: 0;
        }

        .reply-compose-label {
            font-size: 12px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 2px;
        }

        .reply-compose-text {
            font-size: 13px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reply-compose-close {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 16px;
            cursor: pointer;
        }

        /* Confirm modal for delete */
        .chat-confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.12s ease;
        }

        .chat-confirm-overlay.hide {
            opacity: 0;
            transition: opacity 0.12s ease;
        }

        .chat-confirm-dialog {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px 22px 18px;
            width: min(360px, 90vw);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.16);
        }

        .chat-prompt-dialog {
            width: min(440px, 92vw);
        }

        .chat-confirm-dialog h4 {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .chat-confirm-dialog p {
            margin: 0 0 16px;
            color: #475569;
            font-size: 13px;
            line-height: 1.5;
        }

        .chat-prompt-input {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            resize: vertical;
            min-height: 82px;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .chat-prompt-input:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.12);
        }

        .chat-confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .chat-confirm-actions .btn-cancel,
        .chat-confirm-actions .btn-confirm {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .chat-confirm-actions .btn-cancel {
            background: #e2e8f0;
            color: #0f172a;
        }

        .chat-confirm-actions .btn-confirm {
            background: #0f172a;
            color: #ffffff;
        }

        .chat-confirm-actions .btn-cancel:hover { background: #cbd5e1; }
        .chat-confirm-actions .btn-confirm:hover { background: #111827; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .chat-input-container {
            padding: 16px 20px;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            margin-bottom: 60px;
        }

        .chat-input-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .input-wrapper {
            flex: 1;
            position: relative;
            display: flex;
            align-items: flex-end;
        }

        .chat-input {
            width: 100%;
            min-height: 44px;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.5;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
            color: #1e293b;
            resize: none;
            max-height: 120px;
            overflow-y: auto;
        }

        .chat-input::placeholder {
            color: #94a3b8;
        }

        .chat-input:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .attach-button,
        .location-button {
            width: 44px;
            height: 44px;
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .attach-button:hover,
        .location-button:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .media-preview-container {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .remove-media-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .media-filename {
            font-size: 12px;
            color: #64748b;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .location-card {
            background: #0f172a;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #1f2937;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.18);
        }

        .location-card .location-img {
            display: block;
            width: 100%;
            max-width: 280px;
            height: auto;
        }

        .location-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #111827;
            color: #e2e8f0;
            font-size: 12px;
        }

        .location-coord {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .location-btn {
            background: #22d3ee;
            color: #0f172a;
            padding: 6px 10px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .location-btn:hover {
            background: #0ea5e9;
            color: #0f172a;
        }

        .send-button {
            width: 44px;
            height: 44px;
            background: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .send-button:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .send-button:active {
            transform: translateY(0);
        }

        .send-button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .no-chat-selected {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            gap: 16px;
        }

        .no-chat-icon {
            font-size: 64px;
            color: #cbd5e1;
        }

        .no-chat-text {
            color: #475569;
            font-size: 18px;
            font-weight: 600;
        }

        .no-chat-subtext {
            color: #94a3b8;
            font-size: 14px;
        }

        .date-divider {
            text-align: center;
            margin: 20px 0;
        }

        .message.system {
            justify-content: center;
            align-items: center;
        }

        .system-message-notice {
            max-width: min(520px, 92%);
            padding: 8px 14px;
            border-radius: 999px;
            background: #ecfeff;
            border: 1px solid #bae6fd;
            color: #0369a1;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        .system-notice-link {
            color: inherit;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .date-text {
            display: inline-block;
            padding: 6px 14px;
            background: #e2e8f0;
            color: #64748b;
            font-size: 12.5px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Monochrome theme override (selaras chat billing) */
        .admin-chat-container {
            background: #ffffff;
            border: 1px solid #d1d5db;
        }

        .users-sidebar {
            background: #020617;
            border-right-color: #1f2937;
        }

        .sidebar-header {
            background: #020617;
            border-bottom-color: #1f2937;
            box-shadow: none;
        }

        .admin-avatar {
            background: linear-gradient(135deg, #111827 0%, #000000 100%);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
        }

        .admin-status {
            color: #9ca3af;
        }

        .search-box,
        .tab-switcher {
            border-bottom-color: #111827;
        }

        .chat-search-input {
            background: #020617;
            color: #f9fafb;
            border-color: #1f2937;
        }

        .chat-search-input::placeholder {
            color: #94a3b8;
        }

        .chat-search-input:focus {
            background: #020617;
            border-color: #334155;
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
        }

        .tab-button {
            background: #020617;
            color: #cbd5e1;
            border-color: #1f2937;
        }

        .tab-button.active {
            background: #f8fafc;
            color: #0f172a;
            border-color: #e2e8f0;
            box-shadow: 0 8px 20px rgba(148, 163, 184, 0.18);
        }

        .user-item {
            background: #020617;
            border-color: #1f2937;
            box-shadow: none;
        }

        .user-item:hover {
            background: #0b1220;
            border-color: #334155;
            box-shadow: none;
        }

        .user-item.active {
            background: #111827;
            border-color: #475569;
            box-shadow: none;
        }

        .user-avatar {
            background: linear-gradient(135deg, #111827 0%, #000000 100%);
            box-shadow: none;
        }

        .user-name {
            color: #f8fafc;
        }

        .user-type {
            color: #94a3b8;
        }

        .chat-header {
            background: #ffffff;
        }

        .chat-avatar,
        .message.sent .message-avatar {
            background: #111827;
        }

        .message.sent .message-content,
        .send-button {
            background: #111827;
            color: #ffffff;
        }

        .send-button:hover {
            background: #000000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.28);
        }

        .chat-messages {
            background: #ffffff;
        }

        .chat-input {
            background: #ffffff;
            border-color: #d1d5db;
            color: #111827;
        }

        .chat-input:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }

        .attach-button,
        .location-button {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .attach-button:hover,
        .location-button:hover {
            background: #f3f4f6;
            border-color: #111827;
            color: #111827;
        }

        .chat-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-toggle-btn {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            border-radius: 10px;
            height: 38px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-toggle-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* CS chat visual alignment with Admin/Billing chat */
        .admin-chat-container {
            height: calc(100vh - 140px);
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
            margin: 0 auto;
            overflow: hidden;
        }

        .users-sidebar {
            width: 410px;
            background: linear-gradient(180deg, #0b1020 0%, #111b32 52%, #0f172a 100%);
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-header {
            background: transparent;
            padding: 24px 18px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-avatar {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: linear-gradient(135deg, #22c55e 0%, #0ea5e9 100%);
            box-shadow: 0 14px 34px rgba(34, 197, 94, 0.25);
        }

        .admin-info h2 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .search-box {
            padding: 14px 14px 10px;
            border-bottom: none;
        }

        .tab-switcher {
            padding: 0 14px 12px;
            border-bottom: none;
        }

        .tab-button {
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
            border-radius: 11px;
            padding: 10px 12px;
        }

        .tab-button.active {
            background: #22c55e;
            color: #0b1020;
            border-color: #22c55e;
            box-shadow: 0 12px 28px rgba(34, 197, 94, 0.28);
        }

        .chat-search-input {
            background: #0b1020;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            border-radius: 12px;
            min-height: 44px;
        }

        .chat-search-input:focus {
            background: #0b1020;
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.14);
        }

        .user-list {
            padding: 10px 12px 14px;
        }

        .user-item {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            box-shadow: none;
        }

        .user-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(34, 197, 94, 0.42);
            transform: translateX(3px);
        }

        .user-item.active {
            background: rgba(34, 197, 94, 0.15);
            border-color: #22c55e;
            box-shadow: 0 16px 34px rgba(34, 197, 94, 0.16);
        }

        .user-item.pinned {
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.16), rgba(15, 23, 42, 0.92));
            border-color: rgba(250, 204, 21, 0.72);
        }

        .user-avatar {
            background: linear-gradient(135deg, #22c55e 0%, #0ea5e9 100%);
            box-shadow: 0 10px 24px rgba(34, 197, 94, 0.22);
        }

        .user-name {
            color: #f8fafc;
        }

        .user-type {
            color: #cbd5e1;
        }

        .pin-chat-btn {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.14);
            color: #94a3b8;
        }

        .pin-chat-btn:hover {
            background: rgba(250, 204, 21, 0.12);
            border-color: rgba(250, 204, 21, 0.5);
            color: #f8fafc;
        }

        .pin-chat-btn.is-pinned {
            background: #facc15;
            border-color: #facc15;
            color: #111827;
        }

        .pin-badge {
            background: rgba(250, 204, 21, 0.16);
            color: #fde68a;
        }

        .chat-section {
            background: #f8fafc;
        }

        .chat-header {
            background: #ffffff;
            padding: 18px 22px;
            border-bottom: 1px solid #e2e8f0;
        }

        .chat-avatar,
        .message.sent .message-avatar,
        .send-button {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }

        .chat-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .chat-subtitle {
            color: #64748b;
        }

        .chat-messages {
            background: #f8fafc;
            padding: 24px;
        }

        .message.sent .message-content {
            background: #16a34a;
            color: #ffffff;
        }

        .message.received .message-content {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
        }

        .message.received .message-avatar {
            background: #0f172a;
        }

        .chat-input-container {
            margin-bottom: 0;
            padding: 14px 18px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        .chat-input {
            background: #f8fafc;
            border-color: #e2e8f0;
            border-radius: 14px;
        }

        .chat-input:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
        }

        .attach-button,
        .location-button,
        .action-btn,
        .sidebar-toggle-btn {
            border-color: #e2e8f0;
            background: #ffffff;
            color: #475569;
        }

        .attach-button:hover,
        .location-button:hover,
        .action-btn:hover,
        .sidebar-toggle-btn:hover {
            background: #ecfdf5;
            border-color: #22c55e;
            color: #16a34a;
        }

        .system-message-notice {
            background: #ecfdf5;
            border-color: #bbf7d0;
            color: #166534;
            border-radius: 14px;
        }

        @media (max-width: 991px) {
            .admin-chat-container {
                height: calc(100vh - 90px);
                border-radius: 12px;
            }

            .users-sidebar {
                width: 340px;
            }
        }

        @media (max-width: 767px) {
            .admin-chat-container {
                flex-direction: column;
                height: auto;
                min-height: calc(100vh - 90px);
            }

            .users-sidebar {
                width: 100%;
                max-height: 42vh;
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
            }

            .chat-section {
                min-height: 58vh;
            }

            .message-bubble {
                max-width: 82%;
            }

            .sidebar-toggle-btn {
                display: none;
            }
        }

        /* Match Admin/Billing chat colors and boxed message style */
        .admin-avatar,
        .user-avatar,
        .chat-avatar,
        .message.sent .message-avatar,
        .send-button {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%) !important;
            color: #ffffff !important;
        }

        .admin-avatar,
        .user-avatar {
            box-shadow: 0 10px 24px rgba(14, 165, 233, 0.25) !important;
        }

        .tab-button.active {
            background: #0ea5e9 !important;
            border-color: #38bdf8 !important;
            color: #ffffff !important;
            box-shadow: 0 12px 28px rgba(14, 165, 233, 0.28) !important;
        }

        .chat-search-input:focus {
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15) !important;
        }

        .user-item.active {
            background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
            border-color: #38bdf8 !important;
            box-shadow: 0 14px 32px rgba(14, 165, 233, 0.34) !important;
        }

        .user-item:hover {
            border-color: rgba(14, 165, 233, 0.42) !important;
        }

        .chat-messages {
            background: #eef2f7 !important;
        }

        .message-bubble {
            max-width: 64%;
        }

        .message-content {
            padding: 12px 16px !important;
            border-radius: 14px !important;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08) !important;
            border: 1px solid transparent;
        }

        .message.sent .message-content {
            background: #0ea5e9 !important;
            color: #ffffff !important;
            border-color: #0ea5e9 !important;
            border-bottom-right-radius: 6px !important;
        }

        .message.received .message-content {
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #e2e8f0 !important;
            border-bottom-left-radius: 6px !important;
        }

        .message.received .message-avatar {
            background: #64748b !important;
        }

        .message.sent .message-status,
        .message.sent .message-status-text,
        .message.sent .message-info {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .message.sent .message-status.read,
        .message.sent .message-status-text.read {
            color: #ffffff !important;
        }

        .send-button:hover {
            background: #0284c7 !important;
            box-shadow: 0 10px 24px rgba(14, 165, 233, 0.32) !important;
        }

        .chat-input:focus {
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15) !important;
        }

        .attach-button:hover,
        .location-button:hover,
        .action-btn:hover,
        .sidebar-toggle-btn:hover {
            background: #f0f9ff !important;
            border-color: #0ea5e9 !important;
            color: #0284c7 !important;
        }

        .attach-button:hover i,
        .location-button:hover i,
        .action-btn:hover i,
        .sidebar-toggle-btn:hover i,
        .no-chat-icon {
            color: #0ea5e9 !important;
        }

        .system-message-notice {
            background: #e0f2fe !important;
            border-color: #bae6fd !important;
            color: #0369a1 !important;
        }

        /* Final CS theme: match Admin Billing monochrome card style */
        .admin-chat-container {
            background: #090909 !important;
            border: 1px solid #202020 !important;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5) !important;
        }

        .users-sidebar {
            background: linear-gradient(180deg, #020202 0%, #0a0a0a 52%, #141414 100%) !important;
            border-right: 1px solid #202020 !important;
        }

        .sidebar-header {
            background: transparent !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }

        .admin-avatar,
        .user-avatar,
        .chat-avatar,
        .message.sent .message-avatar,
        .send-button {
            background: linear-gradient(135deg, #1a1a1a, #000000) !important;
            color: #ffffff !important;
            border-color: #3f3f46 !important;
        }

        .status-dot {
            background: #ffffff !important;
            box-shadow: 0 0 0 6px rgba(255, 255, 255, 0.14) !important;
        }

        .tab-button {
            border-color: rgba(255, 255, 255, 0.14) !important;
            background: rgba(255, 255, 255, 0.05) !important;
            color: #f3f4f6 !important;
        }

        .tab-button.active {
            background: #ffffff !important;
            color: #000000 !important;
            border-color: #ffffff !important;
            box-shadow: 0 10px 24px rgba(255, 255, 255, 0.18) !important;
        }

        .chat-search-input {
            border-color: rgba(255, 255, 255, 0.16) !important;
            background: rgba(8, 8, 8, 0.72) !important;
            color: #f3f4f6 !important;
        }

        .chat-search-input:focus {
            border-color: #737373 !important;
            background: rgba(8, 8, 8, 0.92) !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.12) !important;
        }

        .user-item {
            background: rgba(13, 13, 13, 0.9) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.38) !important;
        }

        .user-item:hover {
            background: rgba(18, 18, 18, 0.95) !important;
            border-color: rgba(255, 255, 255, 0.34) !important;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.5) !important;
        }

        .user-item.active {
            background: linear-gradient(135deg, #000000, #161616) !important;
            border-color: #ffffff !important;
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.55) !important;
            color: #ffffff !important;
        }

        .user-item.pinned {
            border-color: rgba(250, 204, 21, 0.84) !important;
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.18), rgba(12, 12, 12, 0.96)) !important;
        }

        .pin-chat-btn.is-pinned {
            background: #facc15 !important;
            border-color: #facc15 !important;
            color: #111111 !important;
        }

        .unread-badge {
            background: #ffffff !important;
            color: #000000 !important;
            box-shadow: 0 8px 18px rgba(255, 255, 255, 0.2) !important;
        }

        .chat-section {
            background: #0b0b0b !important;
        }

        .chat-header {
            background: #111111 !important;
            color: #f8fafc !important;
            border-bottom: 1px solid #262626 !important;
        }

        .chat-title {
            color: #f8fafc !important;
        }

        .chat-subtitle {
            color: #a1a1aa !important;
        }

        .chat-messages {
            background: #151515 !important;
        }

        .message-content {
            border-radius: 14px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3) !important;
        }

        .message.sent .message-content {
            background: #000000 !important;
            color: #ffffff !important;
            border-color: #2e2e2e !important;
        }

        .message.received .message-content {
            background: #1a1a1a !important;
            color: #f3f4f6 !important;
            border-color: #2e2e2e !important;
        }

        .message.received .message-avatar {
            background: #27272a !important;
        }

        .message.received .message-info {
            color: #9ca3af !important;
        }

        .chat-input-container {
            background: #111111 !important;
            border-top: 1px solid #262626 !important;
        }

        .chat-input {
            background: #0a0a0a !important;
            border-color: #2e2e2e !important;
            color: #f3f4f6 !important;
        }

        .chat-input:focus {
            border-color: #737373 !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.12) !important;
        }

        .attach-button,
        .location-button,
        .action-btn,
        .sidebar-toggle-btn {
            background: #171717 !important;
            border-color: #2e2e2e !important;
            color: #e5e7eb !important;
        }

        .attach-button:hover,
        .location-button:hover,
        .action-btn:hover,
        .sidebar-toggle-btn:hover {
            background: #262626 !important;
            border-color: #737373 !important;
            color: #ffffff !important;
        }

        .attach-button:hover i,
        .location-button:hover i,
        .action-btn:hover i,
        .sidebar-toggle-btn:hover i,
        .no-chat-icon {
            color: #ffffff !important;
        }

        .send-button:hover {
            background: #262626 !important;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.42) !important;
        }

        .no-chat-text {
            color: #f3f4f6 !important;
        }

        .no-chat-subtext {
            color: #a1a1aa !important;
        }

        .date-text,
        .system-message-notice {
            background: #262626 !important;
            border-color: #3f3f46 !important;
            color: #e5e7eb !important;
            border-radius: 14px !important;
        }

        /* Screenshot-inspired CS sidebar */
        .admin-chat-container {
            border-radius: 28px 16px 16px 28px !important;
            background: #0b0b0d !important;
        }

        .users-sidebar {
            width: 430px !important;
            background: #08090b !important;
            border-right: 1px solid #202124 !important;
            padding-top: 26px;
        }

        .sidebar-header {
            padding: 32px 34px 30px !important;
            border-bottom: 1px solid #18191c !important;
        }

        .admin-avatar {
            width: 124px !important;
            height: 124px !important;
            border-radius: 32px !important;
            margin-bottom: 30px !important;
            background: linear-gradient(145deg, #f7f7f8 0%, #b9bac0 48%, #4f5053 100%) !important;
            color: #050505 !important;
            font-size: 46px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75), 0 18px 42px rgba(0, 0, 0, 0.46) !important;
        }

        .admin-info h2 {
            margin: 0 0 22px !important;
            font-size: 36px !important;
            line-height: 1.05 !important;
            font-weight: 900 !important;
            letter-spacing: -0.05em !important;
            color: #ffffff !important;
        }

        .admin-status {
            font-size: 20px !important;
            color: #d6d6d9 !important;
            gap: 12px !important;
        }

        .status-dot {
            width: 18px !important;
            height: 18px !important;
            background: #f7f7f7 !important;
            box-shadow: 0 0 0 14px rgba(255, 255, 255, 0.12) !important;
        }

        .tab-switcher {
            padding: 28px 32px 18px !important;
        }

        .tab-button {
            min-height: 84px !important;
            border-radius: 999px !important;
            font-size: 28px !important;
            font-weight: 900 !important;
            letter-spacing: -0.04em !important;
            border: 1px solid #f4f4f5 !important;
        }

        .tab-button.active {
            background: #f4f4f5 !important;
            color: #050505 !important;
            border-color: #f4f4f5 !important;
            box-shadow: 0 18px 40px rgba(255, 255, 255, 0.08) !important;
        }

        .search-box {
            padding: 10px 32px 28px !important;
            border-bottom: 1px solid #202124 !important;
        }

        .chat-search-input {
            min-height: 92px !important;
            border-radius: 28px !important;
            padding: 22px 24px 22px 86px !important;
            background: #141517 !important;
            border: 2px solid #36373b !important;
            color: #f6f6f7 !important;
            font-size: 26px !important;
            letter-spacing: -0.03em !important;
        }

        .chat-search-input::placeholder {
            color: #a9b0bd !important;
        }

        .search-icon {
            left: 28px !important;
            color: #a9b0bd !important;
            font-size: 32px !important;
        }

        .user-list {
            padding: 18px 28px 34px !important;
        }

        .user-item {
            min-height: 136px !important;
            margin-bottom: 26px !important;
            padding: 24px 28px !important;
            border-radius: 34px !important;
            background: #141517 !important;
            border: 2px solid #2d2e32 !important;
            box-shadow: none !important;
        }

        .user-item:hover {
            background: #191a1d !important;
            border-color: #4a4b50 !important;
            transform: translateX(0) !important;
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.34) !important;
        }

        .user-item.active {
            background: #1d1e22 !important;
            border-color: #ffffff !important;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.45) !important;
        }

        .user-item-content {
            gap: 24px !important;
        }

        .user-avatar {
            width: 100px !important;
            height: 100px !important;
            border-radius: 30px !important;
            background: linear-gradient(145deg, #f6f6f7 0%, #bcbcc2 58%, #8b8c91 100%) !important;
            color: #050505 !important;
            font-size: 40px !important;
            font-weight: 900 !important;
            box-shadow: none !important;
        }

        .user-name {
            margin-bottom: 10px !important;
            font-size: 28px !important;
            line-height: 1.12 !important;
            font-weight: 900 !important;
            letter-spacing: -0.04em !important;
            color: #f7f7f8 !important;
        }

        .user-type {
            font-size: 22px !important;
            color: #a1a1aa !important;
            letter-spacing: -0.02em !important;
        }

        .pin-chat-btn {
            width: 40px !important;
            height: 40px !important;
            border-radius: 14px !important;
            background: #202124 !important;
            border-color: #34353a !important;
        }

        .unread-badge {
            min-width: 28px !important;
            height: 28px !important;
            font-size: 13px !important;
        }

        @media (max-width: 991px) {
            .users-sidebar {
                width: 360px !important;
            }

            .admin-avatar {
                width: 84px !important;
                height: 84px !important;
                border-radius: 24px !important;
                font-size: 34px !important;
            }

            .admin-info h2 {
                font-size: 28px !important;
            }

            .tab-button,
            .chat-search-input,
            .user-name {
                font-size: 20px !important;
            }

            .tab-button {
                min-height: 60px !important;
            }

            .chat-search-input {
                min-height: 64px !important;
                padding-left: 62px !important;
            }

            .user-avatar {
                width: 70px !important;
                height: 70px !important;
                border-radius: 22px !important;
                font-size: 28px !important;
            }

            .user-item {
                min-height: 98px !important;
                border-radius: 26px !important;
                padding: 18px !important;
            }
        }

        /* Compact left panel + white right chat content */
        .users-sidebar {
            width: 340px !important;
            padding-top: 12px !important;
        }

        .sidebar-header {
            padding: 18px 20px 16px !important;
        }

        .admin-avatar {
            width: 72px !important;
            height: 72px !important;
            border-radius: 20px !important;
            margin-bottom: 14px !important;
            font-size: 28px !important;
        }

        .admin-info h2 {
            margin-bottom: 10px !important;
            font-size: 24px !important;
            letter-spacing: -0.03em !important;
        }

        .admin-status {
            font-size: 14px !important;
            gap: 8px !important;
        }

        .status-dot {
            width: 10px !important;
            height: 10px !important;
            box-shadow: 0 0 0 7px rgba(255, 255, 255, 0.12) !important;
        }

        .tab-switcher {
            padding: 14px 16px 10px !important;
        }

        .tab-button {
            min-height: 48px !important;
            font-size: 15px !important;
            letter-spacing: -0.01em !important;
        }

        .search-box {
            padding: 8px 16px 14px !important;
        }

        .chat-search-input {
            min-height: 50px !important;
            border-radius: 16px !important;
            padding: 12px 14px 12px 48px !important;
            font-size: 15px !important;
        }

        .search-icon {
            left: 18px !important;
            font-size: 18px !important;
        }

        .user-list {
            padding: 12px 14px 18px !important;
        }

        .user-item {
            min-height: 74px !important;
            margin-bottom: 10px !important;
            padding: 12px 14px !important;
            border-radius: 18px !important;
            border-width: 1px !important;
        }

        .user-item-content {
            gap: 12px !important;
        }

        .user-avatar {
            width: 48px !important;
            height: 48px !important;
            border-radius: 14px !important;
            font-size: 18px !important;
        }

        .user-name {
            margin-bottom: 4px !important;
            font-size: 15px !important;
            letter-spacing: 0 !important;
        }

        .user-type {
            font-size: 12px !important;
            letter-spacing: 0 !important;
        }

        .pin-chat-btn {
            width: 30px !important;
            height: 30px !important;
            border-radius: 10px !important;
        }

        .unread-badge {
            width: 20px !important;
            min-width: 20px !important;
            max-width: 20px !important;
            height: 20px !important;
            min-height: 20px !important;
            max-height: 20px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            line-height: 20px !important;
            text-align: center !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            vertical-align: middle !important;
        }

        .admin-chat-container,
        .chat-section,
        .chat-header,
        .chat-messages,
        .chat-input-container {
            background: #ffffff !important;
        }

        .chat-section {
            border-left: 1px solid #e5e7eb !important;
        }

        .chat-header {
            color: #0f172a !important;
            border-bottom: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.03) !important;
        }

        .chat-title {
            color: #0f172a !important;
        }

        .chat-subtitle {
            color: #64748b !important;
        }

        .chat-messages {
            background-color: #f8fafc !important;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.055) 1px, transparent 1px) !important;
            background-size: 32px 32px !important;
            background-position: -1px -1px !important;
        }

        .chat-input-container {
            border-top: 1px solid #e5e7eb !important;
        }

        .chat-input {
            background: #ffffff !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }

        .chat-input:focus {
            border-color: #111827 !important;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1) !important;
        }

        .message-content {
            border-radius: 26px !important;
            padding: 18px 24px !important;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08) !important;
        }

        .message.sent .message-content {
            background: #20232b !important;
            color: #ffffff !important;
            border-color: #20232b !important;
            border-bottom-right-radius: 10px !important;
        }

        .message.received .message-content {
            background: #ffffff !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
            border-bottom-left-radius: 10px !important;
        }

        .message.received .message-info {
            color: #94a3b8 !important;
        }

        .attach-button,
        .location-button,
        .action-btn,
        .sidebar-toggle-btn {
            background: #ffffff !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }

        .attach-button:hover,
        .location-button:hover,
        .action-btn:hover,
        .sidebar-toggle-btn:hover {
            background: #f3f4f6 !important;
            border-color: #111827 !important;
            color: #111827 !important;
        }

        .date-text,
        .system-message-notice {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #475569 !important;
            border-radius: 999px !important;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04) !important;
        }

        /* Right chat panel icons should stay black on the white UI */
        .chat-section .action-btn,
        .chat-section .sidebar-toggle-btn,
        .chat-section .attach-button,
        .chat-section .location-button,
        .chat-section .send-button {
            background: #ffffff !important;
            border: 1px solid #d1d5db !important;
            color: #111827 !important;
        }

        .chat-section .action-btn i,
        .chat-section .sidebar-toggle-btn i,
        .chat-section .attach-button i,
        .chat-section .location-button i,
        .chat-section .send-button i {
            color: #111827 !important;
        }

        .chat-section .action-btn:hover,
        .chat-section .sidebar-toggle-btn:hover,
        .chat-section .attach-button:hover,
        .chat-section .location-button:hover,
        .chat-section .send-button:hover {
            background: #f3f4f6 !important;
            border-color: #111827 !important;
            color: #111827 !important;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08) !important;
        }

        .admin-chat-container.sidebar-minimized .users-sidebar {
            width: 96px !important;
            min-width: 96px !important;
        }

        .admin-chat-container.sidebar-minimized .sidebar-header {
            padding: 14px 10px !important;
        }

        .admin-chat-container.sidebar-minimized .sidebar-header h2,
        .admin-chat-container.sidebar-minimized .admin-status,
        .admin-chat-container.sidebar-minimized .tab-switcher,
        .admin-chat-container.sidebar-minimized .search-box,
        .admin-chat-container.sidebar-minimized .user-details,
        .admin-chat-container.sidebar-minimized .unread-badge,
        .admin-chat-container.sidebar-minimized .pin-chat-btn {
            display: none !important;
        }

        .admin-chat-container.sidebar-minimized .admin-avatar {
            margin: 0 auto !important;
            width: 46px !important;
            height: 46px !important;
            border-radius: 12px !important;
            font-size: 20px !important;
        }

        .admin-chat-container.sidebar-minimized .user-list {
            padding: 8px 8px 12px !important;
        }

        .admin-chat-container.sidebar-minimized .user-item {
            padding: 8px !important;
            margin-bottom: 8px !important;
        }

        .admin-chat-container.sidebar-minimized .user-item-content {
            justify-content: center;
        }

        .admin-chat-container.sidebar-minimized .user-avatar {
            width: 40px !important;
            height: 40px !important;
            margin: 0 !important;
            font-size: 16px !important;
        }

        .load-more-chats-wrap {
            padding: 8px 4px 14px;
        }

        .load-more-chats-btn {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: #202124;
            color: #f8fafc;
            border-radius: 26px;
            padding: 14px 16px;
            font-size: 15px;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .load-more-chats-btn:hover {
            background: #2a2b2f;
            border-color: rgba(255, 255, 255, 0.42);
        }

        .load-more-chats-btn:disabled {
            opacity: 0.65;
            cursor: wait;
        }

        .quick-reply-panel {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .quick-reply-list {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            flex: 1;
        }

        .quick-reply-chip,
        .quick-reply-manage-btn {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            cursor: pointer;
        }

        .quick-reply-chip:hover,
        .quick-reply-manage-btn:hover {
            background: #f3f4f6;
            border-color: #111827;
        }

        .quick-reply-suggestions {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            bottom: calc(100% + 8px);
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 18px 44px rgba(15, 23, 42, 0.14);
            max-height: 260px;
            overflow-y: auto;
            z-index: 40;
        }

        .quick-reply-suggestion {
            width: 100%;
            border: 0;
            background: transparent;
            padding: 12px 14px;
            text-align: left;
            cursor: pointer;
        }

        .quick-reply-suggestion:hover {
            background: #f8fafc;
        }

        .quick-reply-suggestion strong {
            display: block;
            color: #111827;
            font-size: 13px;
        }

        .quick-reply-suggestion span {
            display: block;
            color: #64748b;
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .quick-reply-dialog {
            width: min(560px, calc(100vw - 32px));
        }

        .quick-reply-form {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        .quick-reply-input,
        .quick-reply-textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
            color: #111827;
        }

        .quick-reply-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .quick-reply-manage-list {
            display: grid;
            gap: 8px;
            max-height: 240px;
            overflow-y: auto;
            margin: 14px 0;
        }

        .quick-reply-manage-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .quick-reply-manage-item strong {
            display: block;
            color: #111827;
            font-size: 13px;
        }

        .quick-reply-manage-item span {
            display: block;
            color: #64748b;
            font-size: 12px;
        }

        .quick-reply-item-actions {
            display: flex;
            gap: 6px;
        }

        .quick-reply-item-actions button {
            border: 1px solid #d1d5db;
            background: #ffffff;
            border-radius: 10px;
            padding: 7px 9px;
            cursor: pointer;
        }

        /* Wider and taller CS chat card without overlapping the dashboard sidebar. */
        .container-xxl:has(> .admin-chat-container) {
            width: 100% !important;
            max-width: none !important;
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .admin-chat-container {
            width: 100% !important;
            max-width: none !important;
            height: calc(100dvh - 118px) !important;
            max-height: calc(100dvh - 118px) !important;
            min-height: 0 !important;
            margin: 4px 0 30px !important;
            overflow: hidden !important;
            transform: none !important;
        }

        .users-sidebar {
            width: 400px !important;
            min-width: 400px !important;
        }

        .chat-header {
            padding-left: 32px !important;
            padding-right: 32px !important;
        }

        .chat-messages {
            padding: 32px 40px !important;
        }

        .message-bubble {
            max-width: min(780px, 78%) !important;
        }

        .users-sidebar,
        .chat-section,
        .user-list,
        .chat-messages {
            min-height: 0 !important;
        }

        @media (max-width: 767.98px) {
            .container-xxl:has(> .admin-chat-container) {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }

            .admin-chat-container {
                width: 100% !important;
                height: calc(100dvh - 24px) !important;
                max-height: calc(100dvh - 24px) !important;
                min-height: 0 !important;
                margin: 0 !important;
                border-radius: 18px !important;
            }

            .users-sidebar {
                width: 100% !important;
                min-width: 0 !important;
            }
        }

        html,
        body {
            height: 100% !important;
            overflow: hidden !important;
        }

        .content-wrapper,
        .layout-page,
        .layout-container {
            overflow: hidden !important;
        }

        .container-p-y {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .content-footer {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="admin-chat-container">
        <div class="users-sidebar">
            <div class="sidebar-header">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2>Chat CS</h2>
                    <div class="admin-status">
                        <span class="status-dot"></span>
                        <span>Customer Service</span>
                    </div>
                </div>
            </div>

            <div class="search-box">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="chat-search-input" id="chatSearchInput" placeholder="Cari pelanggan..."
                        autocomplete="off">
                </div>
            </div>

            <div class="tab-switcher">
                <button class="tab-button active" data-filter="all" id="tabAll">All Chat</button>
                <button class="tab-button" data-filter="unread" id="tabUnread">Unread</button>
            </div>

            <div class="user-list" id="userList">
                @foreach($users as $user)
                    <div class="user-item {{ !empty($user['is_pinned']) ? 'pinned' : '' }}" data-user-id="{{ $user['id'] }}" data-user-name="{{ $user['name'] }}" data-nomer-id="{{ $user['nomer_id'] ?? '' }}" data-last-activity="{{ \Illuminate\Support\Carbon::parse($user['last_message_at'] ?? $user['created_at'])->timestamp }}" data-pinned="{{ !empty($user['is_pinned']) ? '1' : '0' }}">
                        <div class="user-item-content">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user['name'], 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ $user['name'] }}</div>
                                <div class="user-meta-row">
                                    <div class="user-type">{{ ($user['nomer_id']) }}</div>
                                </div>
                                <span class="pin-badge"><i class="fas fa-thumbtack"></i> Pinned</span>
                            </div>
                            <span class="unread-badge" id="unread-{{ $user['id'] }}" style="display: none;">0</span>
                            <button type="button" class="pin-chat-btn {{ !empty($user['is_pinned']) ? 'is-pinned' : '' }}" data-pin-user-id="{{ $user['id'] }}" title="{{ !empty($user['is_pinned']) ? 'Lepas pin chat' : 'Pin chat agar tampil paling atas' }}" aria-label="Pin chat {{ $user['name'] }}" aria-pressed="{{ !empty($user['is_pinned']) ? 'true' : 'false' }}">
                                <i class="fas fa-thumbtack"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
                @if(count($users) >= 100)
                    <div class="load-more-chats-wrap" id="loadMoreChatsWrap">
                        <button type="button" class="load-more-chats-btn" id="loadMoreChatsBtn">
                            <i class="fas fa-chevron-down"></i>
                            <span>Lihat chat lainnya</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="chat-section">
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="chat-avatar" id="chatAvatar" style="display: none;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h1 class="chat-title" id="chatTitle">Pilih pelanggan untuk memulai chat</h1>
                        <div class="chat-subtitle" id="chatSubtitle" style="display: none;">? Online</div>
                    </div>
                </div>
                <div class="chat-header-actions">
                    <button type="button" id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="Lebarkan chat">
                        <i class="fas fa-expand-alt"></i>
                        <span id="sidebarToggleText">Lebarkan Chat</span>
                    </button>
                    <div class="chat-actions" id="chatActions" style="display: none;">
                        <button class="action-btn" id="transferChatButton" title="Teruskan ke divisi lain">
                            <i class="fas fa-share-from-square"></i>
                        </button>
                        <button class="action-btn" title="Info">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="action-btn" title="More">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="no-chat-selected">
                    <i class="fas fa-comments no-chat-icon"></i>
                    <div class="no-chat-text">Selamat Datang di Chat CS</div>
                    <div class="no-chat-subtext">Pilih pelanggan dari sidebar untuk memulai percakapan</div>
                </div>
            </div>

            <div class="chat-input-container" id="chatInputContainer" style="display: none;">
                <div class="quick-reply-panel" id="quickReplyPanel" style="display: none;">
                    <div class="quick-reply-list" id="quickReplyList"></div>
                    <button type="button" class="quick-reply-manage-btn" id="quickReplyManageBtn" title="Kelola balasan cepat">
                        <i class="fas fa-bolt"></i> Kelola
                    </button>
                </div>
                <div id="mediaPreview"
                    style="display: none; padding: 8px 12px; background: #f1f5f9; border-radius: 8px; margin-bottom: 8px;">
                </div>
                <div id="replyPreview" class="reply-compose-preview" style="display: none;">
                    <div class="reply-compose-body">
                        <div class="reply-compose-label" id="replyPreviewName">Balas pesan</div>
                        <div class="reply-compose-text" id="replyPreviewText"></div>
                    </div>
                    <button type="button" class="reply-compose-close" id="replyPreviewClose" aria-label="Batalkan reply">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form class="chat-input-form" id="chatForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="receiverId" name="receiver_id">
                    <input type="hidden" id="replyToMessageId" name="reply_to_message_id" value="">
                    <input type="file" id="mediaInput" accept="image/*,video/*" style="display: none;">
                    <button type="button" class="attach-button" id="attachButton" title="Kirim foto/video">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <button type="button" class="location-button" id="locationButton" title="Kirim lokasi">
                        <i class="fas fa-location-arrow"></i>
                    </button>
                    <div class="input-wrapper">
                        <div class="quick-reply-suggestions" id="quickReplySuggestions"></div>
                        <textarea class="chat-input" id="messageInput" placeholder="Tulis pesan Anda..."
                            autocomplete="off" rows="1"></textarea>
                    </div>
                    <button type="submit" class="send-button" id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        window.userId = "{{ Auth::id() }}";
        window.userName = "{{ Auth::user()->name }}";
        window.isAdmin = true;
        window.selectedUserId = null;
        window.chatSessionId = null;

        (function () {
            const container = document.querySelector('.admin-chat-container');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const toggleText = document.getElementById('sidebarToggleText');
            const storageKey = 'adminCSSidebarMinimized';

            if (!container || !toggleBtn) return;

            const applyState = (isMinimized) => {
                container.classList.toggle('sidebar-minimized', isMinimized);
                if (toggleText) {
                    toggleText.textContent = isMinimized ? 'Tampilkan Sidebar' : 'Lebarkan Chat';
                }
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.className = isMinimized ? 'fas fa-compress-alt' : 'fas fa-expand-alt';
                }
            };

            let isMinimized = false;
            try {
                isMinimized = localStorage.getItem(storageKey) === '1';
            } catch (_) {}

            applyState(isMinimized);

            toggleBtn.addEventListener('click', function () {
                isMinimized = !container.classList.contains('sidebar-minimized');
                applyState(isMinimized);
                try {
                    localStorage.setItem(storageKey, isMinimized ? '1' : '0');
                } catch (_) {}
            });
        })();

    </script>
    @vite(['resources/js/chat.js'])
@endsection
