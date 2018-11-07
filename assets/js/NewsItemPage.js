import React from 'react'
import ReactDOM from 'react-dom'
import NewsItem from '../js/NewsItem'

const root = document.getElementById('root');
ReactDOM.render(<NewsItem {...(root.dataset)} />, root);
