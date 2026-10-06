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
        if (!window.ethereum)
            return swal("Not Connected", "Please connect wallet", "error");
        if (!amount.value)
            return swal("No Amount", "Please enter amount to import", "error");
        if (amount.value < 0)
            return swal("Invalid", "Invalid input entered", "error");
        if (amount.value < 10)
            return swal("Sorry", "Minimum deposit amount is 10$", "error");
        if (amount.value > 10000)
            return swal("Sorry", "Maximun deposit amount is 10000$", "error");
        document.querySelector(".transferBtn").innerHTML =
            "Wait! Processing...";

        const accounts = await ethereum.request({
            method: "eth_requestAccounts",
        });
        const chainId = await ethereum.request({ method: "eth_chainId" });
        console.log(chainId + "" + accounts);
        //Ensure connected to BSC mainnet
        if (chainId != 56) {
            // 0x38 is the chain ID for Binance Smart Chain Mainnet
            swal(
                "Wrong Network",
                "Please connect to Binance Smart Chain Mainnet",
                "error",
            );
            return;
        }

        const provider = new ethers.providers.Web3Provider(window.ethereum);
        const signer = provider.getSigner(accounts[0]);
        const tokenAddress = "0x55d398326f99059ff775485246999027b3197955"; // USDT BEP20 address
        const contract = new ethers.Contract(tokenAddress, tokenAbi, signer);

        const tx = await contract.transfer(
            "0x812f6784B3E9eAae424287ca99986374E98747C6", // Receiver address
            ethers.utils.parseUnits(amount.value, 18), // Convert to smallest unit (18 decimals for USDT)
        );

        await tx.wait();
        const txnid = tx.hash;
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
                //alert(response['value']);
                window.location.reload();
            },
            error: function () {
                alert("error");
            },
        });
    } catch (error) {
        console.log(error);
        swal(
            "Checkout",
            "Please check your wallet account balance",
            "error",
        ).then(function () {
            window.location.reload();
        });
    }
}
transferButton.addEventListener("click", depositActivation);
