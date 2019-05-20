/**
 * @typedef {Object} OpenGraphTag
 * @property {string[]} values
 */

/**
 * Service for building and storing HTML page Meta-Tags
 */
export default {
    data() {
        return {
            pageMeta: {
                title: '',
                brand: '',
                brandDivider: ' | ',
                brandFirst: false,
                fullTitle: '',                
                tags: {
                    meta: {},
                    /** @var { Object.<string, OpenGraphTag> } */
                    openGraph: {}
                },
                _DOM_META_CLASS: '__pagems' // Required to detect own elements
            }
        }
    },

    methods: {
        /**
         * Sets HTML <title> suffix e.g. "title | brand", usually a project name or brand
         * @param {string} value 
         */
        $pageMetaSetBrand(value) {
            this.pageMeta.brand = value;
            this._updateFullTitle();
            return this;
        },

        /**
         * Sets HTML <title> first value
         * @param {string} value
         */
        $pageMetaSetTitle(value) {
            this.pageMeta.title = value;
            this._updateFullTitle();
            return this;
        },

        /**
         * Sets HTML <meta> tag with the specified name
         * @param {string} name 
         * @param {string} content 
         */
        $pageMetaSetMeta(name, content) {
            this.pageMeta.tags.meta[name] = content;
            return this;
        },

        /**
         * Appends string to the HTML <meta> tag with the specified name
         * @param {string} name 
         * @param {string} content 
         */
        $pageMetaAppendMeta(name, content) {
            if(!this.pageMeta.tags.meta.hasOwnProperty(name)) {
                this.pageMeta.tags.meta[name] = '';
            }
            this.pageMeta.tags.meta[name] += content;
            return this;
        },

        /**
         * Sets OpenGraph HTML <meta> tag with the specified property
         * @param {string} property 
         * @param {string} content 
         */
        $pageMetaSetOpenGraph(property, content) {
            this.pageMeta.tags.openGraph[property] = {
                values: [content]
            };
            return this;
        },

        /**
         * Adds a new array value to the OpenGraph HTML <meta> tag with the specified property
         * @param {string} property 
         * @param {string} content
         */
        $pageMetaAddOpenGraph(property, anotherContent) {
            if(!this.pageMeta.tags.openGraph.hasOwnProperty(name)) {
                this.pageMeta.tags.openGraph[property] = {
                    values: []
                };
            }
            this.pageMeta.tags.openGraph[property].values.push(anotherContent);
            return this;
        },

        _updateFullTitle() {
            if(this.pageMeta.title) {
                if(this.pageMeta.brand) {
                    this.pageMeta.fullTitle = this.pageMeta.brandFirst 
                        ? (this.pageMeta.brand + this.pageMeta.brandDivider + this.pageMeta.title)
                        : (this.pageMeta.title + this.pageMeta.brandDivider + this.pageMeta.brand);
                } else {
                    this.pageMeta.fullTitle = this.pageMeta.title;
                }
            } else {
                this.pageMeta.fullTitle = this.pageMeta.brand;
            }

            this._updateHeadDOM(true);
        },

        _updateHeadDOM(titleOnly) {
            document.title = this.pageMeta.fullTitle;

            if(titleOnly) { return; }

            // remove known metas
            let head = document.getElementsByTagName('head')[0];
            let metas = head.getElementsByClassName(this._DOM_META_CLASS);
            for (let i = 0; i < metas.length; ++i) {
                head.removeChild(metas[i]);
            }
    
            // add new metas
            for(let name in this.pageMeta.tags.meta) {
                let e = document.createElement('meta');
                e.name = name;
                e.content = this.pageMeta.tags[name];
                e.class = this._DOM_META_CLASS;
                head.appendChild(e);
            }
    
            // add new OpenGraph metas
            for(let property in this.pageMeta.tags.openGraph) {
                this.pageMeta.tags[property].values.forEach(content => {
                    let e = document.createElement('meta');
                    e.property = property;
                    e.content = content;
                    e.class = this._DOM_META_CLASS;
                    head.appendChild(e);
                });
            }
        }
    },

    mounted() {
        this._updateHeadDOM();
    }
}