import request from '../utils/request'

export function addCollectivity(collectivity) {
  return request.post('/api/collectivity', collectivity)
}
export function updateCollectivity(id, collectivity) {
  return request.post(`/api/collectivity/${id}`, collectivity)
}

export function addOrUpdateCollectivity(id, collectivity) {
  return id
    ? updateCollectivity(id, collectivity)
    : addCollectivity(collectivity)
}
