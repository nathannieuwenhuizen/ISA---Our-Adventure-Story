define(["require", "exports", "url", "patreon"], function (require, exports, url_1, patreon_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var PatreonObject = /** @class */ (function () {
        function PatreonObject() {
            this.CLIENT_ID = 'KdUXDDsA01kaI2EZiJQ0UsnIICK0mhPVBi6YeMGwxJKTmK9VgoWRd3vnYUPuiWvh';
            this.CLIENT_SECRET = 'IcN4YFb3dW3xvB6jdv-kb9xDzGJ4LHB_ym8NbgxayVFj0cbsi0ShTvfclgF0Qohb';
            this.patreonOAuthClient = patreon_1.oauth(this.CLIENT_ID, this.CLIENT_SECRET);
            this.redirectURL = 'https://ourinteractivetgcaption.000webhostapp.com/';
            // var patreonOAuthClient = oauth(this.CLIENT_ID, this.CLIENT_SECRET);
            // console.log(patreonOAuthClient);
        }
        PatreonObject.prototype.handleOAuthRedirectRequest = function (request, response) {
            var oauthGrantCode = url_1.default.parse(request.url, true).query.code;
            this.patreonOAuthClient
                .getTokens(oauthGrantCode, this.redirectURL)
                .then(function (tokensResponse) {
                var patreonAPIClient = patreon_1.patreon(tokensResponse.access_token);
                return patreonAPIClient('/current_user');
            })
                .then(function (_a) {
                var store = _a.store;
                // store is a [JsonApiDataStore](https://github.com/beauby/jsonapi-datastore)
                // You can also ask for result.rawJson if you'd like to work with unparsed data
                response.end(store.findAll('user').map(function (user) { return user.serialize(); }));
            })
                .catch(function (err) {
                console.error('error!', err);
                response.end(err);
            });
        };
        return PatreonObject;
    }());
    exports.default = PatreonObject;
});
