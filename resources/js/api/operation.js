import request from '@/utils/request';
import Resource from '@/api/resource';

class OperationResource extends Resource {
  constructor() {
    super('operations');
  }

  operations(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/operations',
      method: 'get',
    });
  }

}

export { OperationResource as default };
