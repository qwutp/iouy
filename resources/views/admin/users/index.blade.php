@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="admin-header">
            <div class="admin-header-top">
                <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">← Назад к панели управления</a>
            </div>
            <div class="admin-header-main">
                <h1 class="admin-header-title">Управление пользователями</h1>
            </div>
            <p class="admin-header-subtitle">Просмотр и управление аккаунтами пользователей</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="admin-content">
            @if($users->count() > 0)
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Аватар</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background-color: #e0e0e0;">
                                        @if($user->avatar)
                                            <img src="{{ asset('images/avatars/' . $user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="/placeholder.svg?height=40&width=40" alt="User" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="admin-role-badge admin-role-admin">Администратор</span>
                                    @else
                                        <span class="admin-role-badge admin-role-user">Пользователь</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="admin-table-actions">
                                        @if(!$user->is_admin || $user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-table-btn admin-table-btn-delete">Удалить</button>
                                            </form>
                                        @else
                                            <span class="admin-table-disabled">Нельзя удалить</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="admin-pagination">
                    {{ $users->links() }}
                </div>
            @else
                <div class="admin-empty-state">
                    <h3>Здесь еще нет пользователей</h3>
                    <p>Пользователи появятся после регистрации на сайте</p>
                </div>
            @endif
        </div>
    </div>
@endsection
