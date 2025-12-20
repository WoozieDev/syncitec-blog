import { computed, ref } from 'vue'
import { updateTheme } from '@/composables/useAppearance'

export type Theme = 'light' | 'dark' | 'system'

const STORAGE_KEY = 'appearance'

// Single source of truth for theme in your app
const theme = ref<Theme>((localStorage.getItem(STORAGE_KEY) as Theme) ?? 'system')

export function useTheme() {
  const setTheme = (value: Theme) => {
    theme.value = value
    localStorage.setItem(STORAGE_KEY, value)
    updateTheme(value) // uses starter-kit behavior (cookie + html.dark)
  }

  const toggleTheme = () => {
    const next = theme.value === 'dark' ? 'light' : 'dark'
    setTheme(next)
  }

  const isDark = computed(() => theme.value === 'dark')

  return { theme, isDark, setTheme, toggleTheme }
}
