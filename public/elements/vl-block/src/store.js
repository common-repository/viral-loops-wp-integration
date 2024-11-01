import apiFetch from '@wordpress/api-fetch';
import { registerStore } from '@wordpress/data';

/**
 * Selectors
 */
const selectors = {
    getOption( state, optionKey ) {
        const { option } = state;
        return option;
    },
};

/**
 * Resolvers
 */
const resolvers = {
    *getOption( optionKey ) {
        const option = yield actions.getOption(
            '/vl-routes/campaigns',
        );
        return actions.setOption( option );
    },
};

/**
 * Actions
 */
const actions = {
	setOption( option ) {
		return {
			type: 'SET_OPTION',
			option,
		};
	},
	getOption( path ) {
		return {
			type: 'GET_OPTION',
			path,
		};
	},
};

/**
 * Controls
 */
const controls = {
    GET_OPTION( action ) {
        return apiFetch( { path: action.path } );
    },
};

/**
 * Reducer
 *
 * @param {object} state
 * @param {string} action
 */
function reducer( state = { option: '' }, action ) {
    switch ( action.type ) {
        case 'SET_OPTION':
            return {
                ...state,
                option: action.option,
            };
    }
    return state;
};

/**
 * Register Store
 */
const store = registerStore(
    'wcltd/wholesome-plugin/data',
    {
        actions,
        controls,
        reducer,
        resolvers,
        selectors,
    }
);

export default store;
