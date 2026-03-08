import { BrowserRouter } from 'react-router-dom'
import Layout from './components/layout/Layout'
import ActiveComponent from './routing/ActiveComponent'

function App() {
	return (
		<BrowserRouter>
			<Layout>
				<ActiveComponent />
			</Layout>
		</BrowserRouter>
	)
}

export default App
