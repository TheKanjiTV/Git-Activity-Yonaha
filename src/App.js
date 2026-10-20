import './App.css';
import Card from './components/ui/Card';
import Button from './components/ui/Button';
import Form from './components/ui/Form';
import Counter from './components/ui/Counter';
import List from './components/ui/List';
import Post from './components/ui/Post';

function App() {
    return (
        <div className="App">
            <Card
                name="Kenji"
                description="This is me."
            />

            <Card
                name="Yonaha"
                description="This is me again."
            />

            <Button
                text="Click Me"
                onClick={() => alert("Hello Kenji!")}
            />

            <Form />

            <Counter />

            <List />
            
            <Post />
        </div>
    );
}

export default App;