import Link from "next/link"

export default function LoginPage() {
  return (
    <div className="min-h-screen bg-gray-400">
      <header className="bg-white border-b border-gray-300 px-4 py-2 flex items-center justify-between">
        <div className="flex items-center gap-2">
          <Link href="/" className="font-bold text-sm">
            silly games
          </Link>
        </div>
      </header>

      <main className="p-4 flex items-center justify-center min-h-[calc(100vh-56px)]">
        <div className="bg-white rounded-md p-6 w-full max-w-md">
          <h2 className="text-sm font-medium mb-4 text-center">Вход в личный кабинет</h2>
          <form className="space-y-4">
            <div>
              <label className="block text-xs mb-1">Логин</label>
              <input type="text" className="w-full border border-gray-300 rounded p-2 text-xs" />
            </div>
            <div>
              <label className="block text-xs mb-1">Пароль</label>
              <input type="password" className="w-full border border-gray-300 rounded p-2 text-xs" />
            </div>
            <div className="flex justify-center">
              <button type="submit" className="bg-gray-200 text-xs py-1.5 px-4 rounded">
                Войти
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  )
}
