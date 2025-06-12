import Link from "next/link"
import { ChevronDown, Search, ThumbsUp, MessageSquare } from "lucide-react"

export default function Home() {
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
        <div className="grid grid-cols-1 gap-4">
          <section className="bg-white rounded-md p-4">
            <h2 className="text-sm font-medium mb-4">Недавние игры</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="bg-gray-200 rounded-md aspect-video"></div>
              <div className="bg-gray-200 rounded-md aspect-video"></div>
              <div className="bg-gray-200 rounded-md aspect-video"></div>
            </div>
            <div className="mt-4 flex justify-between items-center">
              <button className="bg-gray-200 text-xs py-1 px-3 rounded">Загрузить</button>
              <div className="flex items-center gap-2">
                <button className="bg-gray-200 text-xs py-1 px-3 rounded">Купить новую</button>
                <button className="bg-green-500 text-white text-xs py-1 px-3 rounded flex items-center gap-1">
                  <span>Играть</span>
                  <span className="bg-green-600 text-xs py-0.5 px-1 rounded">423 ₽</span>
                </button>
              </div>
            </div>
          </section>

          <section className="bg-white rounded-md p-4">
            <h2 className="text-sm font-medium mb-4">Заголовок</h2>
            <div className="text-xs text-gray-500 mb-4">
              <p className="mb-2">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.
              </p>
              <p>
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
                laborum.
              </p>
            </div>

            <div className="bg-gray-100 rounded-md p-3 mb-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="text-xs">
                  <div className="font-medium mb-1">Системные требования:</div>
                  <div className="text-gray-500 space-y-1">
                    <p>ОС: Windows 10</p>
                    <p>Процессор: Intel Core i5</p>
                    <p>Память: 8 GB RAM</p>
                  </div>
                </div>
                <div className="text-xs">
                  <div className="font-medium mb-1">Рекомендуемые:</div>
                  <div className="text-gray-500 space-y-1">
                    <p>ОС: Windows 11</p>
                    <p>Процессор: Intel Core i7</p>
                    <p>Память: 16 GB RAM</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section className="bg-white rounded-md p-4">
            <h2 className="text-sm font-medium mb-4">Отзывы</h2>
            <div className="space-y-4">
              {[1, 2, 3].map((i) => (
                <div key={i} className="bg-gray-100 rounded-md p-3">
                  <div className="flex items-center gap-2 mb-2">
                    <div className="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-xs">
                      <ThumbsUp className="w-3 h-3" />
                    </div>
                    <div className="text-xs font-medium">silly_user</div>
                  </div>
                  <div className="text-xs text-gray-500">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                  </div>
                </div>
              ))}
            </div>
          </section>
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
