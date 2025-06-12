import Link from "next/link"
import { ChevronDown, Search, ThumbsUp, MessageSquare } from "lucide-react"

export default function ProfilePage() {
  return (
    <div className="min-h-screen bg-gray-400">
      <header className="bg-white border-b border-gray-300 px-4 py-2 flex items-center justify-between">
        <div className="flex items-center gap-2">
          <Link href="/" className="font-bold text-sm">
            silly games
          </Link>
          <nav className="hidden md:flex items-center space-x-4 ml-6">
            <Link href="#" className="text-xs uppercase">
              Магазин
            </Link>
            <Link href="#" className="text-xs uppercase">
              Форумы
            </Link>
            <Link href="#" className="text-xs uppercase">
              Настройки
            </Link>
          </nav>
        </div>
        <div className="flex items-center gap-2">
          <div className="relative">
            <Search className="w-4 h-4 absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400" />
            <input type="text" className="bg-gray-100 rounded-md text-xs py-1 pl-2 pr-8 w-32" placeholder="Поиск" />
          </div>
          <div className="flex items-center gap-1 text-xs">
            <span>username</span>
            <ChevronDown className="w-3 h-3" />
          </div>
        </div>
      </header>

      <main className="p-4">
        <div className="bg-white rounded-md p-4">
          <div className="flex flex-col md:flex-row gap-4">
            <div className="flex flex-col items-center gap-2">
              <div className="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center">
                <svg
                  className="w-10 h-10 text-gray-400"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                  <path
                    d="M20 20C20 17.5 16.4183 15.5 12 15.5C7.58172 15.5 4 17.5 4 20"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </div>
              <div className="text-xs font-medium">silly_user</div>
              <div className="flex gap-1">
                <button className="bg-gray-200 text-xs py-1 px-2 rounded">Мои игры</button>
                <button className="bg-gray-200 text-xs py-1 px-2 rounded">Список желаемого</button>
              </div>
            </div>

            <div className="flex-1">
              <h2 className="text-sm font-medium mb-3">История покупок</h2>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {[1, 2, 3, 4, 5, 6, 7, 8].map((i) => (
                  <div key={i} className="space-y-1">
                    <div className="bg-gray-200 rounded-md aspect-video"></div>
                    <div className="text-xs">
                      <div className="font-medium">Название игры</div>
                      <div className="text-gray-500 flex justify-between">
                        <span>Дата: 12.04.23</span>
                        <span>1,299 ₽</span>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </main>

      <footer className="p-4 flex justify-center items-center gap-4 text-xs text-gray-500">
        <div className="flex items-center gap-1">
          <ThumbsUp className="w-3 h-3" />
          <span>25</span>
        </div>
        <div className="flex items-center gap-1">
          <MessageSquare className="w-3 h-3" />
          <span>10</span>
        </div>
      </footer>
    </div>
  )
}
