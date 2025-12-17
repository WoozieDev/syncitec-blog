export type CommentStatus = 'pending' | 'approved' | 'rejected'

export interface CommentRow {
  id: number
  body: string
  status: CommentStatus
  created_at: string
  user: { id: number; name: string; email: string }
  post: { id: number; title: string; slug: string }
}
