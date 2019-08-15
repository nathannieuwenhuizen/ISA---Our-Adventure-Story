import url from 'url';
import { patreon as patreonAPI , oauth as patreonOAuth } from 'patreon';

export default class PatreonObject {
    const CLIENT_ID = 'KdUXDDsA01kaI2EZiJQ0UsnIICK0mhPVBi6YeMGwxJKTmK9VgoWRd3vnYUPuiWvh';
    const CLIENT_SECRET = 'IcN4YFb3dW3xvB6jdv-kb9xDzGJ4LHB_ym8NbgxayVFj0cbsi0ShTvfclgF0Qohb';
    const patreonOAuthClient = patreonOAuth(this.CLIENT_ID, this.CLIENT_SECRET);
    
    const redirectURL = 'https://ourinteractivetgcaption.000webhostapp.com/';
    constructor() {
        // var patreonOAuthClient = oauth(this.CLIENT_ID, this.CLIENT_SECRET);
        // console.log(patreonOAuthClient);
    }
    handleOAuthRedirectRequest(request: any, response: any) {
        const oauthGrantCode = url.parse(request.url, true).query.code
    
        this.patreonOAuthClient
            .getTokens(oauthGrantCode, this.redirectURL)
            .then(tokensResponse => {
                const patreonAPIClient = patreonAPI(tokensResponse.access_token)
                return patreonAPIClient('/current_user')
            })
            .then(({ store }) => {
                // store is a [JsonApiDataStore](https://github.com/beauby/jsonapi-datastore)
                // You can also ask for result.rawJson if you'd like to work with unparsed data
                response.end(store.findAll('user').map(user => user.serialize()))
            })
            .catch(err => {
                console.error('error!', err)
                response.end(err)
            })
    


}