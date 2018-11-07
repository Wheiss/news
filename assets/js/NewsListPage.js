import React from 'react'
import ReactDOM from 'react-dom'
import NewsList from '../js/NewsList'

const root = document.getElementById('root');
ReactDOM.render(<NewsList {...(root.dataset)} />, root);
