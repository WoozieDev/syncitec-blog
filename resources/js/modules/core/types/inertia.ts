

export interface AuthUser {
  id: number
  name: string
  email: string
  avatar?: string | null
}

export interface AuthProps {
  user?: AuthUser
  roles?: string[]
  permissions?: string[]
}

export interface AdminPageProps {
  title?: string
  auth?: AuthProps
}
