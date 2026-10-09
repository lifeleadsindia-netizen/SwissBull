
const contractAddress = "0x55d398326f99059ff775485246999027b3197955";
const tokenAbi = [
    {
        inputs: [],
        name: "decimals",
        outputs: [
            {
                internalType: "uint8",
                name: "",
                type: "uint8",
            },
        ],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transfer",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "from",
                type: "address",
            },
            {
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "transferFrom",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            {
                internalType: "address",
                name: "spender",
                type: "address",
            },
            {
                internalType: "uint256",
                name: "amount",
                type: "uint256",
            },
        ],
        name: "approve",
        outputs: [
            {
                internalType: "bool",
                name: "",
                type: "bool",
            },
        ],
        stateMutability: "nonpayable",
        type: "function",
    },
];


const transferButton = document.querySelector(".transferBtn");
const amount = document.querySelector("#amount");
const memberid = document.querySelector("#memberid");
const csrf = document.querySelector("#csrf");
const route = document.querySelector("#route").value;

async function depositActivation() {
    try {
        // if (!window.ethereum)
        //     return new swal("Not Connected", "Please connect wallet", "error");
        if (!amount.value)
            return new swal("No Amount", "Please enter amount to import", "error");
        if (amount.value < 0)
            return new swal("Invalid", "Invalid input entered", "error");
        if (amount.value < 1)
            return new swal("Sorry", "Minimum deposit amount is 1$", "error");
        if (amount.value > 10000)
            return new swal("Sorry", "Maximun deposit amount is 10000$", "error");
        document.querySelector(".transferBtn").innerHTML =
            "Wait! Processing...";


        // const accounts = await ethereum.request({ method: "eth_requestAccounts" });
        // const chainId = await ethereum.request({ method: "eth_chainId" });

        // // Ensure connected to BSC mainnet
        // if (chainId != 56) { // 0x38 is the chain ID for Binance Smart Chain Mainnet
        //     swal("Wrong Network", "Please connect to Binance Smart Chain Mainnet", "error");
        //     return;
        // }

        // const provider = new ethers.providers.Web3Provider(window.ethereum);
        // const signer = provider.getSigner(accounts[0]);
        // const tokenAddress = "0x55d398326f99059ff775485246999027b3197955"; // USDT BEP20 address
        // const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);

        // const tx = await contract.transfer(
        //     "0x7E385112745c5BD1c1a62e82Bb03222e0fc4e483", // Receiver address
        //     ethers.utils.parseUnits(amount.value, 18) // Convert to smallest unit (18 decimals for USDT)
        // );

        // await tx.wait();
        const txnid = Math.floor(1000000 + Math.random() * 9000000); // tx.hash;
        //Ajax code for activation
        $.ajax({
            url: route,
            type: "POST",
            data: {
                memberid: memberid.value,
                amount: amount.value,
                txnid: txnid,
                _token: csrf.value,
            },
            success: function (response) {
                new swal(
                    "Success",
                    "Deposit has been completed successfully.",
                    "success"
                ).then(function () {
                    window.location.reload();
                });
            },
            error: function () {
                alert("error");
            },
        });
    } catch (error) {
        console.log(error);
        new swal(
            "Checkout",
            "Please check your wallet account balance",
            "error"
        ).then(function () {
            window.location.reload();
        });
    }
}
transferButton.addEventListener("click", depositActivation);