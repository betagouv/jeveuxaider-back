import request from '../utils/request'

export function fetchReleases() {
  return request.get('/api/releases')
}
