const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
    allowEIO3: true,
    cors: {
        origin: "http://localhost:8080",
        methods: ["GET", "POST"],
        credentials: true
    }
})
httpServer.listen(12345, function () {
    console.log('listening on *:12345')
})
io.on('connection', function (socket) {
    console.log(`client ${socket.id} has connected`)
    socket.on('newTransaction', function (transaction) {
        socket.broadcast.emit('newTransaction', transaction)
    })
    socket.on('deletedUser', function (user) {
        socket.broadcast.emit('deletedUser', user)
    })
    socket.on('editedUser', function (user) {
        socket.broadcast.emit('editedUser', user)
    })
})